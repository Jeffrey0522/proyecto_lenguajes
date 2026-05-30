<?php

class UsuarioModel
{

    protected $db;

    public function __construct()
    {
        require 'libs/SPDO.php';
        $this->db= SPDO::singleton();
    } // constructor

    public function listar()
    {
        $consulta = $this->db->prepare('call sp_obtener_usuarios()');
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        $consulta->closeCursor();
        return $resultado;
    } // listar

    public function registrarUsuario($cedula, $nombre, $apellido, $correo, $nombreUsuario, $contrasena, $rol, $nombreUsuarioAdmin)
    {
        $consulta = $this->db->prepare("call sp_registrar_usuario(?, ?, ?, ?, ?, ?, ?, ?)");
        $params = array($cedula, $nombre, $apellido, $correo, $contrasena, $rol, $nombreUsuario, $nombreUsuarioAdmin);
        $consulta->execute($params);
        $consulta->closeCursor();
    }

    public function login($nombreUsuario)
    {
        $consulta = $this->db->prepare("call sp_login(?)");
        $consulta->execute(array($nombreUsuario));
        $resultado = $consulta->fetch();
        $consulta->closeCursor();
        return $resultado;
    }

    public function cambiarContrasena($nombreUsuario, $contrasenaNueva)
    {
        $consulta = $this->db->prepare("call sp_actualizar_contrasena(?, ?)");
        $consulta->execute(array($nombreUsuario, $contrasenaNueva));
        $consulta->closeCursor();
    }

    public function eliminarUsuario($nombreUsuario, $nombreUsuarioAdmin)
    {
        $consulta = $this->db->prepare("call sp_eliminar_usuario(?, ?)");
        $consulta->execute(array($nombreUsuario, $nombreUsuarioAdmin));
        $consulta->closeCursor();
    }

    public function habilitarUsuario($nombreUsuario, $nombreUsuarioAdmin)
    {
        $consulta = $this->db->prepare("call sp_habilitar_usuario(?, ?)");
        $consulta->execute(array($nombreUsuario, $nombreUsuarioAdmin));
        $consulta->closeCursor();
    }

    public function buscarUsuario($busqueda)
    {
        $consulta = $this->db->prepare('call sp_buscar_usuario(?)');
        $consulta->execute(array($busqueda));
        $resultado = $consulta->fetch();
        $consulta->closeCursor();
        return $resultado;
    }

    public function actualizarUsuario($nombre, $apellido, $correo, $nombreUsuario, $rol, $cedula, $nombreUsuarioAdmin)
    {
        $consulta = $this->db->prepare("call sp_actualizar_usuario(?, ?, ?, ?, ?, ?, ?)");
        $consulta->execute(array($nombre, $apellido, $correo, $nombreUsuario, $rol, $cedula, $nombreUsuarioAdmin));
        $consulta->closeCursor();
    }

    public function sumarIntento($nombreUsuario)
    {
        $consulta = $this->db->prepare("call sp_sumar_intentos(?)");
        $consulta->execute(array($nombreUsuario));
        $consulta->closeCursor();
    }

    public function bloquearUsuario($nombreUsuario, $intentos, $hasta)
    {
        $consulta = $this->db->prepare("call sp_bloquear_usuario(?, ?, ?)");
        $consulta->execute(array($nombreUsuario, $intentos, $hasta));
        $consulta->closeCursor();
    }

    public function resetearIntentos($nombreUsuario)
    {
        $consulta = $this->db->prepare("call sp_resetear_intentos_fallidos(?)");
        $consulta->execute(array($nombreUsuario));
        $consulta->closeCursor();
    }

    public function obtenerSuperusuarios()
    {
        $consulta = $this->db->prepare('call sp_obtener_super_admin_activos()');
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        $consulta->closeCursor();
        return $resultado;
    }

} // fin clase