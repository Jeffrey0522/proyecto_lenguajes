<?php

require_once 'libs/SPDO.php';

class gavetaModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function registrar($codigo, $descripcion, $codigoGabinete, $idUsuario)
    {
        $consulta = $this->db->prepare("CALL sp_registrar_gaveta(?, ?, ?, ?)");
        $resultado = $consulta->execute(array($codigo, $descripcion, $codigoGabinete, $idUsuario));
        $consulta->closeCursor();
        return $resultado;
    }

    public function listar()
    {
        $consulta = $this->db->prepare("CALL sp_listar_gavetas()");
        $consulta->execute();

        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarPorCodigo($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_buscar_gaveta_por_codigo(?)");
        $consulta->execute(array($codigo));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    public function existeCodigo($codigo)
    {
        $resultado = $this->buscarPorCodigo($codigo);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizar($codigo, $descripcion, $codigoGabinete)
    {
        $consulta = $this->db->prepare("CALL sp_actualizar_gaveta(?, ?, ?)");
        $resultado = $consulta->execute(array($codigo, $descripcion, $codigoGabinete));
        $consulta->closeCursor();
        return $resultado;
    }

    public function eliminar($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_gaveta(?)");
        if (!$consulta->execute(array($codigo))) {
            $error = $consulta->errorInfo();
            throw new Exception($error[2]);
        }
        $consulta->closeCursor();
        return true;
    }
}
