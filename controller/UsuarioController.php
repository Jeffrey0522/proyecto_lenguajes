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
        $this->view->show("registrarUsuarioView.php", null);
    }

    public function registrarUsuario()
    {
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
        $this->view->show("formularioLoginView.php", null);
    }

    public function login()
    {
        session_start();
        $nombreUsuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];
        $usuarioLogin = $this->usuario->login($nombreUsuario);
        if ($usuarioLogin != null && password_verify($contrasena, $usuarioLogin['contrasena'])) {
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
                default:
                    # code...
                    break;
            }
            exit();
        } else {
            header("Location: ?controlador=Usuario&accion=formularioLogin");
            exit();
        }
    }

    public function formularioCambiarContrasena()
    {
        $this->view->show("cambiarContrasenaView.php", null);
    }

    public function cambiarContrasena()
    {
        $nombreUsuario = $_POST['nombreUsuario'];
        $nuevaContrasena = $_POST['nuevaContrasena'];
        $contrasenaHash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);
        $this->usuario->cambiarContrasena($nombreUsuario, $contrasenaHash);
    }

    public function vistaSuperAdmin()
    {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['rol'] != '1') {
            $this->cerrarSesion();
            exit();
        }
        $data['usuarios'] = $this->usuario->listar();
        $this->view->show("superAdminView.php", $data);
    }

    public function vistaAdminContenido()
    {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['rol'] != '2') {
            $this->cerrarSesion();
            exit();
        }
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
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['rol'] != '1') {
            $this->cerrarSesion();
            exit();
        }
        $nombreUsuario = $_POST['nombre_usuario'];
        $this->usuario->eliminarUsuario($nombreUsuario);
        header("Location: ?controlador=Usuario&accion=vistaSuperAdmin");
    }

    public function formularioActualizar()
    {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['rol'] != '1') {
            $this->cerrarSesion();
            exit();
        }
        $cedula = $_POST['cedula'];
        $usuario = $this->usuario->buscarUsuario($cedula);
        $this->view->show("actualizarUsuarioView.php", $usuario);
    }

    public function actualizarUsuario()
    {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['rol'] != '1') {
            $this->cerrarSesion();
            exit();
        }
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $nombreUsuario = $_POST['nombre_usuario'];
        $rol = $_POST['rol'];
        $cedula = $_POST['cedula'];
        $this->usuario->actualizarUsuario($nombre, $apellido, $correo, $nombreUsuario, $rol, $cedula);
        header("Location: ?controlador=Usuario&accion=vistaSuperAdmin");
    }
} // fin clase