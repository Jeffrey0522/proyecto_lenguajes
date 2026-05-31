<?php

require_once 'libs/SPDO.php';

class cajaModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function registrar($codigo, $descripcion, $capacidad, $idUsuario)
    {
        $consulta = $this->db->prepare("CALL sp_registrar_caja(?, ?, ?, ?)");
        $resultado = $consulta->execute(array($codigo, $descripcion, $capacidad, $idUsuario));
        $consulta->closeCursor();
        return $resultado;
    }

    public function listar()
    {
        $consulta = $this->db->prepare("CALL sp_listar_cajas()");
        $consulta->execute();

        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarPorCodigo($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_buscar_caja_por_codigo(?)");
        $consulta->execute(array($codigo));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    public function existeCodigo($codigo)
    {
        return $this->buscarPorCodigo($codigo) ? true : false;
    }

    public function actualizar($codigo, $descripcion, $capacidad)
    {
        $consulta = $this->db->prepare("CALL sp_actualizar_caja(?, ?, ?)");
        $resultado = $consulta->execute(array($codigo, $descripcion, $capacidad));
        $consulta->closeCursor();
        return $resultado;
    }

    public function eliminar($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_caja(?)");
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

    public function tieneCapacidadDisponible($codigoCaja)
    {
        $consulta = $this->db->prepare("CALL sp_verificar_capacidad_caja(?)");
        $consulta->execute(array($codigoCaja));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        if ($resultado) {
            return $resultado['tiene_capacidad'] == 1;
        }

        return false;
    }

    public function capacidadValida($codigoCaja, $nuevaCapacidad)
    {
        $consulta = $this->db->prepare("CALL sp_validar_capacidad_caja(?, ?)");
        $consulta->execute(array($codigoCaja, $nuevaCapacidad));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        if ($resultado) {
            return $resultado['capacidad_valida'] == 1;
        }

        return false;
    }
}//class
