<?php

require_once 'libs/SPDO.php';

class bitacoraModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    // =====================
    // REGISTRAR BITÁCORA
    // =====================
    public function registrar($usuario, $accion, $tabla, $idRegistro, $cedulaRegistro)
    {
        $consulta = $this->db->prepare("CALL sp_registrar_bitacora(?, ?, ?, ?, ?)");
        $resultado = $consulta->execute(array(
            $usuario,
            $accion,
            $tabla,
            $idRegistro,
            $cedulaRegistro
        ));
        $consulta->closeCursor();
        return $resultado;
    }

    // =====================
    // LISTAR BITÁCORAS
    // =====================
    public function listar($desde, $hasta, $usuario)
    {
        $consulta = $this->db->prepare("CALL sp_listar_bitacoras(?, ?, ?)");
        $consulta->execute(array($desde, $hasta, $usuario));

        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();

        return $resultado;
    }
}