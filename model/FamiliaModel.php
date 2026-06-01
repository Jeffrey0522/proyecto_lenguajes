<?php

require_once 'libs/SPDO.php';

class FamiliaModel
{

    private $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function listarFamilias()
    {

        $consulta = $this->db->prepare("CALL sp_listar_familias()");

        $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $datos;
    }

    public function insertarFamilia($nombre, $idOrden, $idUsuario)
    {

        $consulta = $this->db->prepare("CALL sp_insertar_familia(?, ?, ?)");

        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $idOrden);
        $consulta->bindParam(3, $idUsuario);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }

    public function buscarFamilias($busqueda, $idOrden = 0)
    {

        $consulta = $this->db->prepare("CALL sp_buscar_familias(?, ?)");

        $consulta->bindParam(1, $busqueda);
        $consulta->bindParam(2, $idOrden);

        $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $datos;
    }

    public function actualizarFamilia($id, $nombre, $idOrden)
    {

        $consulta = $this->db->prepare("CALL sp_actualizar_familia(?, ?, ?)");

        $consulta->bindParam(1, $id);
        $consulta->bindParam(2, $nombre);
        $consulta->bindParam(3, $idOrden);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }

    public function eliminarFamilia($id)
    {
        $validacionSubFamilias = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM sub_familias
        WHERE id_familia = ?
    ");

        $validacionSubFamilias->bindParam(1, $id);
        $validacionSubFamilias->execute();
        $resultadoSubFamilias = $validacionSubFamilias->fetch(PDO::FETCH_ASSOC);
        $validacionSubFamilias->closeCursor();

        if ($resultadoSubFamilias['total'] > 0) {
            return false;
        }

        $validacionGeneros = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM generos
        WHERE id_familia = ?
    ");

        $validacionGeneros->bindParam(1, $id);
        $validacionGeneros->execute();
        $resultadoGeneros = $validacionGeneros->fetch(PDO::FETCH_ASSOC);
        $validacionGeneros->closeCursor();

        if ($resultadoGeneros['total'] > 0) {
            return false;
        }

        $consulta = $this->db->prepare("CALL sp_eliminar_familia(?)");

        $consulta->bindParam(1, $id);

        $resultado = $consulta->execute();

        $consulta->closeCursor();

        return $resultado;
    }
    public function existeFamilia($nombre)
    {

        $consulta = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM familias WHERE nombre = ?"
        );

        $consulta->bindParam(1, $nombre);

        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $resultado['total'] > 0;
    }
}
