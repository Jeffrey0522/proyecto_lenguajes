<?php

require_once 'libs/SPDO.php';

class ComentarioModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function listarEspecimenesConComentarios($busqueda = "")
    {
        $consulta = $this->db->prepare("CALL sp_listar_especimenes_con_comentarios(?)");
        $consulta->execute(array($busqueda));
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function listarComentariosPorEspecimen($codigoEspecimen)
    {
        $consulta = $this->db->prepare("CALL sp_listar_comentarios_admin(?)");
        $consulta->execute(array($codigoEspecimen));
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function obtenerCodigoPorComentario($idComentario)
    {
        $consulta = $this->db->prepare(
            "SELECT codigo_especimen FROM comentarios WHERE id = ?"
        );
        $consulta->execute(array($idComentario));
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado ? $resultado['codigo_especimen'] : null;
    }

    public function eliminarComentario($idComentario, $cedulaAdmin)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_comentario(?, ?)");
        $resultado = $consulta->execute(array($idComentario, $cedulaAdmin));
        $consulta->closeCursor();
        return $resultado;
    }
}
