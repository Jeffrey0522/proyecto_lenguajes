<?php

require 'model/UsuarioModel.php';

class UsuarioController
{

    private $view;
    private $usuario;

    public function __construct()
    {
        $this->view = new View();
        $this->usuario = new UsuarioModel();
    } // constructor

    public function mostrar() {} // listar

    public function formularioCrearUsuario()
    {
        $this->protegerRuta('1');
        $contrasenaGenerada = $this->generarContrasena();
        $data['contrasenaGenerada'] = $contrasenaGenerada;
        $this->view->show("registrarUsuarioView.php", $data);
    }

    public function registrarUsuario()
    {
        $this->protegerRuta('1');
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $nombreUsuario = $_POST['nombre_usuario'];
        $rol = $_POST['rol'];
        $contrasena = $_POST['contrasena'];
        $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);

        $this->usuario->registrarUsuario($cedula, $nombre, $apellido, $correo, $nombreUsuario, $contrasenaHash, $rol);

        $this->formularioCrearUsuario();
    }

    public function formularioLogin()
    {
        session_start();
        $this->view->show("formularioLoginView.php", null);
    }

    public function login()
    {
        session_start();
        $nombreUsuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];

        $MAX_INTENTOS    = 3;
        $BLOQUEO_MINUTOS = 1;

        $usuarioLogin = $this->usuario->login($nombreUsuario);

        // Usuario no existe
        if ($usuarioLogin == null) {
            $_SESSION['login_error'] = "Credenciales inválidas.";
            header("Location: ?controlador=Usuario&accion=formularioLogin");
            exit();
        }

        // ¿Está bloqueado?
        if ($usuarioLogin['bloqueado_hasta'] !== null) {
            $ahora = new DateTime();
            $bloqueado = new DateTime($usuarioLogin['bloqueado_hasta']);

            if ($ahora < $bloqueado) {
                $diff = $ahora->diff($bloqueado);
                $_SESSION['login_error'] = "Cuenta bloqueada. Intente en {$diff->i} min {$diff->s} seg.";
                header("Location: ?controlador=Usuario&accion=formularioLogin");
                exit();
            }

            // Bloqueo expirado → resetear
            $this->usuario->resetearIntentos($nombreUsuario);
            $usuarioLogin['intentos_fallidos'] = 0;
            $usuarioLogin['bloqueado_hasta'] = null;
        }

        // ¿Cuenta activa?
        if ($usuarioLogin['activo'] == "0") {
            $_SESSION['login_error'] = "Cuenta desactivada. Contacte al administrador.";
            header("Location: ?controlador=Usuario&accion=formularioLogin");
            exit();
        }

        // Contraseña incorrecta
        if (!password_verify($contrasena, $usuarioLogin['contrasena'])) {
            $this->usuario->sumarIntento($nombreUsuario);

            $intentosFallidos = $usuarioLogin['intentos_fallidos'] + 1;

            if ($intentosFallidos >= $MAX_INTENTOS) {
                $hasta = (new DateTime())->modify("+{$BLOQUEO_MINUTOS} minutes")->format('Y-m-d H:i:s');
                $this->usuario->bloquearUsuario($nombreUsuario, $intentosFallidos, $hasta);
                $_SESSION['login_error'] = "Demasiados intentos. Cuenta bloqueada por {$BLOQUEO_MINUTOS} minutos.";
            } else {
                $restantes = $MAX_INTENTOS - $intentosFallidos;
                $_SESSION['login_error'] = "Contraseña incorrecta. Intentos restantes: {$restantes}.";
            }

            header("Location: ?controlador=Usuario&accion=formularioLogin");
            exit();
        }

        // Login exitoso → resetear contadores
        $this->usuario->resetearIntentos($nombreUsuario);

        $_SESSION['nombreUsuario'] = $usuarioLogin['nombre'];
        $_SESSION['apellidoUsuario'] = $usuarioLogin['apellido'];
        $_SESSION['username'] = $usuarioLogin['nombre_usuario'];
        $_SESSION['rol'] = $usuarioLogin['rol'];

        switch ($usuarioLogin['rol']) {
            case '1':
                header("Location: ?controlador=Usuario&accion=vistaSuperAdmin");
                break;
            case '2':
                header("Location: ?controlador=Usuario&accion=vistaAdminContenido");
                break;
        }
        exit();
    }

    public function formularioCambiarContrasena()
    {
        session_start();
        $this->view->show("cambiarContrasenaView.php", null);
    }

    public function cambiarContrasena()
    {
        $this->protegerRuta();
        $nombreUsuario = $_POST['nombreUsuario'];
        $contrasena = $_POST['contrasena'];
        $nuevaContrasena = $_POST['nuevaContrasena'];
        $confirmarContrasena = $_POST['confirmarContrasena'];

        $usuario = $this->usuario->buscarUsuario($nombreUsuario);
        if (!is_null($usuario)) {
            if (!password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['cambio_contrasena_error'] = "Credenciales inválidas.";
            } else {
                if ($nuevaContrasena != $confirmarContrasena) {
                    $_SESSION['cambio_contrasena_error'] = "Las contraseñas no coinciden";
                } else {
                    $contrasenaHash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);
                    $this->usuario->cambiarContrasena($nombreUsuario, $contrasenaHash);
                }
            }
        }
        $_SESSION['cambio_contrasena_error'] = "Nombre de usuario no existe";
        exit();
    }

    public function vistaSuperAdmin()
    {
        $this->protegerRuta('1');
        $data['usuarios'] = $this->usuario->listar();
        $this->view->show("superAdminView.php", $data);
    }

    public function vistaAdminContenido()
    {
        $this->protegerRuta('2');
        $this->view->show("adminContenidoView.php", null);
    }

    public function cerrarSesion()
    {
        session_start();
        session_destroy();
        header("Location: ?");
        exit();
    }

    public function eliminarUsuario()
    {
        $this->protegerRuta('1');
        $nombreUsuario = $_POST['nombre_usuario'];
        $this->usuario->eliminarUsuario($nombreUsuario);
        header("Location: ?controlador=Usuario&accion=vistaSuperAdmin");
    }

    public function formularioActualizar()
    {
        $this->protegerRuta('1');
        $cedula = $_POST['cedula'];
        $usuario = $this->usuario->buscarUsuario($cedula);
        $this->view->show("actualizarUsuarioView.php", $usuario);
    }

    public function actualizarUsuario()
    {
        $this->protegerRuta('1');
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $nombreUsuario = $_POST['nombre_usuario'];
        $rol = $_POST['rol'];
        $cedula = $_POST['cedula'];
        $this->usuario->actualizarUsuario($nombre, $apellido, $correo, $nombreUsuario, $rol, $cedula);
        header("Location: ?controlador=Usuario&accion=vistaSuperAdmin");
    }

    public function habilitarUsuario()
    {
        $this->protegerRuta('1');
        $nombreUsuario = $_POST['nombre_usuario'];
        $this->usuario->habilitarUsuario($nombreUsuario);
        header("Location: ?controlador=Usuario&accion=vistaSuperAdmin");
    }

    private function generarContrasena()
    {
        $mayus = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $minus = 'abcdefghijklmnopqrstuvwxyz';
        $nums  = '0123456789';
        $all   = $mayus . $minus . $nums;
        $pwd   = '';
        $pwd  .= $mayus[rand(0, strlen($mayus) - 1)];
        $pwd  .= $nums[rand(0, strlen($nums) - 1)];
        for ($i = strlen($pwd); $i < 8; $i++) {
            $pwd .= $all[rand(0, strlen($all) - 1)];
        }
        return str_shuffle($pwd);
    }

    private function protegerRuta($rolRequerido = null)
    {
        session_start();
        if (!isset($_SESSION['username'])) {
            $this->cerrarSesion();
            exit();
        }
        if ($rolRequerido !== null && $_SESSION['rol'] !== $rolRequerido) {
            $this->cerrarSesion();
            exit();
        }
    }
} // fin clase