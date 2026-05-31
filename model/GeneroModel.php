<?php

require_once 'libs/SPDO.php';

class GeneroModel {

    private $db;

    public function __construct() {
        $this->db = SPDO::singleton();
    }

    public function listarGeneros() {
        $consulta = $this->db->prepare("CALL sp_listar_generos()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function insertarGenero($nombre, $idFamilia, $idSubFamilia, $idUsuario) {
        $consulta = $this->db->prepare("CALL sp_insertar_genero(?, ?, ?, ?)");
        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $idFamilia);
        $consulta->bindParam(3, $idSubFamilia);
        $consulta->bindParam(4, $idUsuario);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarGeneros($busqueda, $idFamilia = 0, $idSubFamilia = 0) {
        $consulta = $this->db->prepare("CALL sp_buscar_generos(?, ?, ?)");
        $consulta->bindParam(1, $busqueda);
        $consulta->bindParam(2, $idFamilia);
        $consulta->bindParam(3, $idSubFamilia);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function actualizarGenero($id, $nombre, $idFamilia, $idSubFamilia) {
        $consulta = $this->db->prepare("CALL sp_actualizar_genero(?, ?, ?, ?)");
        $consulta->bindParam(1, $id);
        $consulta->bindParam(2, $nombre);
        $consulta->bindParam(3, $idFamilia);
        $consulta->bindParam(4, $idSubFamilia);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }

    public function eliminarGenero($id) {
        $validacion = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM especies
            WHERE id_genero = ?
        ");
        $validacion->bindParam(1, $id);
        $validacion->execute();
        $resultado = $validacion->fetch(PDO::FETCH_ASSOC);
        $validacion->closeCursor();

        if ($resultado['total'] > 0) {
            return false;
        }

        $consulta = $this->db->prepare("CALL sp_eliminar_genero(?)");
        $consulta->bindParam(1, $id);
        $resultado = $consulta->execute();
        $consulta->closeCursor();
        return $resultado;
    }
    

public function existeGenero($nombre) {
    $consulta = $this->db->prepare(
        "SELECT COUNT(*) AS total FROM generos WHERE nombre = ?"
    );

    $consulta->bindParam(1, $nombre);
    $consulta->execute();

    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado['total'] > 0;
}
}
?>