<?php

require_once 'libs/SPDO.php';

class gabineteModel
{

    protected $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function registrar($codigo, $ubicacion, $idUsuario)
    {
        $consulta = $this->db->prepare("CALL sp_registrar_gabinete(?, ?, ?)");

        if (!$consulta->execute(array($codigo, $ubicacion, $idUsuario))) {
            $error = $consulta->errorInfo();
            throw new Exception($error[2]);
        }

        $consulta->closeCursor();
        return true;
    }

    public function listar()
    {
        $consulta = $this->db->prepare("CALL sp_listar_gabinetes()");
        $consulta->execute();

        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarPorCodigo($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_buscar_gabinete_por_codigo(?)");
        $consulta->execute([$codigo]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($codigo, $ubicacion)
    {
        $consulta = $this->db->prepare("CALL sp_actualizar_gabinete(?, ?)");
        return $consulta->execute([$codigo, $ubicacion]);
    }

    public function eliminar($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_gabinete(?)");
        if (!$consulta->execute(array($codigo))) {
            $error = $consulta->errorInfo();
            throw new Exception($error[2]);
        }
        $consulta->closeCursor();
        return true;
    }

    public function existeGabinete($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_buscar_gabinete_por_codigo(?)");
        $consulta->execute(array($codigo));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }
}
