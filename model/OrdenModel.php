<?php

require_once 'libs/SPDO.php';

class OrdenModel
{

    private $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function listarOrdenes()
    {
        $consulta = $this->db->prepare("CALL sp_listar_ordenes()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function insertarOrden($nombre, $idUsuario)
    {
        $consulta = $this->db->prepare("CALL sp_insertar_orden(?, ?)");
        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $idUsuario);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarOrden($busqueda)
    {
        $consulta = $this->db->prepare("CALL sp_buscar_orden(?)");
        $consulta->bindParam(1, $busqueda);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function actualizarOrden($id, $nombre)
    {
        $consulta = $this->db->prepare("CALL sp_actualizar_orden(?, ?)");
        $consulta->bindParam(1, $id);
        $consulta->bindParam(2, $nombre);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }

    public function eliminarOrden($id)
    {
        $validacionFamilias = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM familias
            WHERE id_orden = ?
        ");
        $validacionFamilias->bindParam(1, $id);
        $validacionFamilias->execute();
        $resultadoFamilias = $validacionFamilias->fetch(PDO::FETCH_ASSOC);
        $validacionFamilias->closeCursor();

        if ($resultadoFamilias['total'] > 0) {
            return 'familias';
        }

        $validacionSubfamilias = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM sub_familias
            WHERE id_orden = ?
        ");
        $validacionSubfamilias->bindParam(1, $id);
        $validacionSubfamilias->execute();
        $resultadoSubfamilias = $validacionSubfamilias->fetch(PDO::FETCH_ASSOC);
        $validacionSubfamilias->closeCursor();

        if ($resultadoSubfamilias['total'] > 0) {
            return 'subfamilias';
        }

        $consulta = $this->db->prepare("CALL sp_eliminar_orden(?)");
        $consulta->bindParam(1, $id);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }
    public function existeOrden($nombre)
    {
        $consulta = $this->db->prepare(
            "CALL sp_existe_orden(?)"
        );

        $consulta->bindParam(1, $nombre);

        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $resultado['total'] > 0;
    }
}
