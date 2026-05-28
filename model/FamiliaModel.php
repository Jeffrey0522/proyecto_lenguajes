<?php

require_once 'libs/SPDO.php';

class FamiliaModel {

    private $db;

    public function __construct() {
        $this->db = SPDO::singleton();
    }

    public function listarFamilias() {

        $consulta = $this->db->prepare("CALL sp_listar_familias()");

        $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $datos;
    }

    public function insertarFamilia($nombre, $idOrden, $idUsuario) {

        $consulta = $this->db->prepare("CALL sp_insertar_familia(?, ?, ?)");

        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $idOrden);
        $consulta->bindParam(3, $idUsuario);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }

    public function buscarFamilias($busqueda, $idOrden = 0) {

        $consulta = $this->db->prepare("CALL sp_buscar_familias(?, ?)");

        $consulta->bindParam(1, $busqueda);
        $consulta->bindParam(2, $idOrden);

        $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $datos;
    }

    public function actualizarFamilia($id, $nombre, $idOrden) {

        $consulta = $this->db->prepare("CALL sp_actualizar_familia(?, ?, ?)");

        $consulta->bindParam(1, $id);
        $consulta->bindParam(2, $nombre);
        $consulta->bindParam(3, $idOrden);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }

    public function eliminarFamilia($id) {

        $consulta = $this->db->prepare("CALL sp_eliminar_familia(?)");

        $consulta->bindParam(1, $id);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }
}
?>