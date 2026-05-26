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

    public function registrarUsuario($cedula, $nombre, $apellido, $correo, $nombreUsuario, $contrasena, $rol)
    {
        $consulta = $this->db->prepare("call sp_registrar_usuario(?, ?, ?, ?, ?, ?, ?)");
        $params = array($cedula, $nombre, $apellido, $correo, $contrasena, $rol, $nombreUsuario);
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

    public function eliminarUsuario($nombreUsuario)
    {
        $consulta = $this->db->prepare("call sp_eliminar_usuario(?)");
        $consulta->execute(array($nombreUsuario));
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

    public function actualizarUsuario($nombre, $apellido, $correo, $nombreUsuario, $rol, $cedula)
    {
        $consulta = $this->db->prepare("call sp_actualizar_usuario(?, ?, ?, ?, ?, ?)");
        $consulta->execute(array($nombre, $apellido, $correo, $nombreUsuario, $rol, $cedula));
        $consulta->closeCursor();
    }

} // fin clase