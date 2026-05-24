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

    public function formularioSuperAdmin()
    {
        $this->view->show("registrarSuperAdminView.php", null);
    }

    public function registrarUsuario()
    {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $nombreUsuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];
        $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);

        $this->usuario->registrarSuperAdmin($cedula, $nombre, $apellido, $correo, $nombreUsuario, $contrasenaHash);

        $this->formularioSuperAdmin();
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
            header("Location: ?controlador=Usuario&accion=vistaSuperAdmin");
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
        if (!isset($_SESSION['username'])) {
            header("Location: ?");
            exit();
        }
        $this->view->show("superAdminView.php", null);
    }

    public function cerrarSesion()
    {
        session_start();
        session_destroy();
        header("Location: ?");
        exit();
    }
} // fin clase