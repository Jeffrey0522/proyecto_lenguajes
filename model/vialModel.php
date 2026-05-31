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
        try {
            if (!$consulta->execute(array($codigo, $medioConservacion, $codigoCaja))) {
                $error = $consulta->errorInfo();
                throw new Exception($this->limpiarMensajeBD($error[2]));
            }
        } catch (PDOException $e) {
            throw new Exception($this->limpiarMensajeBD($e->getMessage()));
        }
        $consulta->closeCursor();
        return true;
    }
    
    public function eliminar($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_vial(?)");
        try {
            if (!$consulta->execute(array($codigo))) {
                $error = $consulta->errorInfo();
                throw new Exception($this->limpiarMensajeBD($error[2]));
            }
        } catch (PDOException $e) {
            throw new Exception($this->limpiarMensajeBD($e->getMessage()));
        }
        $consulta->closeCursor();
        return true;
    }

    private function limpiarMensajeBD($mensaje)
    {
        if (strpos($mensaje, '1644') !== false) {
            $partes = explode('1644', $mensaje, 2);
            return trim($partes[1]);
        }

        return $mensaje;
    }
}
