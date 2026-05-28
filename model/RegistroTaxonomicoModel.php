<?php

require_once 'libs/SPDO.php';

class RegistroTaxonomicoModel {

    private $db;

    public function __construct() {
        $this->db = SPDO::singleton();
    }

    public function listarOrdenes() {
        $consulta = $this->db->prepare("CALL sp_listar_ordenes()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerFamiliasPorOrden($idOrden) {
        $consulta = $this->db->prepare("CALL sp_obtener_familias_por_orden(?)");
        $consulta->bindParam(1, $idOrden);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerSubfamiliasPorFamilia($idFamilia) {
        $consulta = $this->db->prepare("CALL sp_obtener_subfamilias_por_familia(?)");
        $consulta->bindParam(1, $idFamilia);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerSubfamiliasPorOrden($idOrden) {
        $consulta = $this->db->prepare("CALL sp_obtener_subfamilias_por_orden(?)");
        $consulta->bindParam(1, $idOrden);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerGenerosPorFamilia($idFamilia) {
        $consulta = $this->db->prepare("CALL sp_obtener_generos_por_familia(?)");
        $consulta->bindParam(1, $idFamilia);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerGenerosPorSubfamilia($idSubFamilia) {
        $consulta = $this->db->prepare("CALL sp_obtener_generos_por_subfamilia(?)");
        $consulta->bindParam(1, $idSubFamilia);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerEspeciesPorGenero($idGenero) {
        $consulta = $this->db->prepare("CALL sp_obtener_especies_por_genero(?)");
        $consulta->bindParam(1, $idGenero);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }
}
?>