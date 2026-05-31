<?php

require_once 'libs/SPDO.php';

class EspecieModel {

    private $db;

    public function __construct() {
        $this->db = SPDO::singleton();
    }

    public function listarEspecies() {

        $consulta = $this->db->prepare("CALL sp_listar_especies()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function insertarEspecie($nombreCientifico, $nombreComun, $descripcion, $idGenero, $idUsuario) {

        $consulta = $this->db->prepare("CALL sp_insertar_especie(?, ?, ?, ?, ?)");
        $consulta->bindParam(1, $nombreCientifico);
        $consulta->bindParam(2, $nombreComun);
        $consulta->bindParam(3, $descripcion);
        $consulta->bindParam(4, $idGenero);
        $consulta->bindParam(5, $idUsuario);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarEspecies($busqueda) {

        $consulta = $this->db->prepare("CALL sp_buscar_especies(?, ?)");
        $idGenero = 0;
        $consulta->bindParam(1, $busqueda);
        $consulta->bindParam(2, $idGenero);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function actualizarEspecie($id, $nombreCientifico, $nombreComun, $descripcion, $idGenero) {

        $consulta = $this->db->prepare("CALL sp_actualizar_especie(?, ?, ?, ?, ?)");
        $consulta->bindParam(1, $id);
        $consulta->bindParam(2, $nombreCientifico);
        $consulta->bindParam(3, $nombreComun);
        $consulta->bindParam(4, $descripcion);
        $consulta->bindParam(5, $idGenero);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }

    public function eliminarEspecie($id) {

        $validacion = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM especimenes
            WHERE id_especie = ?
        ");

        $validacion->bindParam(1, $id);
        $validacion->execute();
        $resultado = $validacion->fetch(PDO::FETCH_ASSOC);
        $validacion->closeCursor();
        if ($resultado['total'] > 0) {
            return false;
        }
        $consulta = $this->db->prepare("CALL sp_eliminar_especie(?)");
        $consulta->bindParam(1, $id);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }
    public function existeNombreCientifico($nombreCientifico) {

    if ($nombreCientifico === null) {
        return false;
    }

    $consulta = $this->db->prepare(
        "SELECT COUNT(*) AS total FROM especies WHERE nombre_cientifico = ?"
    );

    $consulta->bindParam(1, $nombreCientifico);
    $consulta->execute();

    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado['total'] > 0;
}

public function existeNombreComun($nombreComun) {

    if ($nombreComun === null) {
        return false;
    }

    $consulta = $this->db->prepare(
        "SELECT COUNT(*) AS total FROM especies WHERE nombre_comun = ?"
    );

    $consulta->bindParam(1, $nombreComun);
    $consulta->execute();

    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado['total'] > 0;
}
}
?>