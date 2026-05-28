<?php

require_once 'libs/SPDO.php';

class vialModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function registrar($codigo, $medioConservacion, $codigoCaja, $idUsuario)
    {
        $consulta = $this->db->prepare("CALL sp_registrar_vial(?, ?, ?, ?)");
        $resultado = $consulta->execute(array($codigo, $medioConservacion, $codigoCaja, $idUsuario));
        $consulta->closeCursor();
        return $resultado;
    }

    public function listar()
    {
        $consulta = $this->db->prepare("CALL sp_listar_viales()");
        $consulta->execute();

        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarPorCodigo($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_buscar_vial_por_codigo(?)");
        $consulta->execute(array($codigo));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    public function existeCodigo($codigo)
    {
        return $this->buscarPorCodigo($codigo) ? true : false;
    }

    public function actualizar($codigo, $medioConservacion, $codigoCaja)
    {
        $consulta = $this->db->prepare("CALL sp_actualizar_vial(?, ?, ?)");
        $resultado = $consulta->execute(array($codigo, $medioConservacion, $codigoCaja));
        $consulta->closeCursor();
        return $resultado;
    }

    public function eliminar($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_vial(?)");
        $resultado = $consulta->execute(array($codigo));
        $consulta->closeCursor();
        return $resultado;
    }
}
