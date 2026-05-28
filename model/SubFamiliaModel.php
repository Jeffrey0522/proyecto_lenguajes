<?php

require_once 'libs/SPDO.php';

class SubFamiliaModel {

    private $db;

    public function __construct() {
        $this->db = SPDO::singleton();
    }

    public function listarSubFamilias() {

        $consulta = $this->db->prepare("CALL sp_listar_subfamilias()");

        $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $datos;
    }

    public function insertarSubFamilia($nombre, $idOrden, $idFamilia, $idUsuario) {

        $consulta = $this->db->prepare("CALL sp_insertar_subfamilia(?, ?, ?, ?)");

        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $idOrden);
        $consulta->bindParam(3, $idFamilia);
        $consulta->bindParam(4, $idUsuario);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }

    public function buscarSubFamilias($busqueda, $idOrden = 0, $idFamilia = 0) {

        $consulta = $this->db->prepare("CALL sp_buscar_subfamilias(?, ?, ?)");

        $consulta->bindParam(1, $busqueda);
        $consulta->bindParam(2, $idOrden);
        $consulta->bindParam(3, $idFamilia);

        $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $datos;
    }

    public function actualizarSubFamilia($id, $nombre, $idOrden, $idFamilia) {

        $consulta = $this->db->prepare("CALL sp_actualizar_subfamilia(?, ?, ?, ?)");

        $consulta->bindParam(1, $id);
        $consulta->bindParam(2, $nombre);
        $consulta->bindParam(3, $idOrden);
        $consulta->bindParam(4, $idFamilia);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }

    public function eliminarSubFamilia($id) {

        $validacion = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM generos
            WHERE id_sub_familia = ?
        ");

        $validacion->bindParam(1, $id);

        $validacion->execute();

        $resultado = $validacion->fetch(PDO::FETCH_ASSOC);

        $validacion->closeCursor();

        if ($resultado['total'] > 0) {
            return false;
        }

        $consulta = $this->db->prepare("CALL sp_eliminar_subfamilia(?)");

        $consulta->bindParam(1, $id);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }
}
?>