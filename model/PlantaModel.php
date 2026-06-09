<?php

require_once 'libs/SPDO.php';

class PlantaModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function registrar($nombre_comun, $nombre_cientifico, $descripcion)
    {
        $consulta = $this->db->prepare("CALL sp_registrar_planta(?, ?, ?)");
        $resultado = $consulta->execute(array($nombre_comun, $nombre_cientifico, $descripcion));
        $consulta->closeCursor();
        return $resultado;
    }


    public function listar($busqueda = "")
    {
        $consulta = $this->db->prepare("CALL sp_listar_plantas(?)");
        $consulta->execute(array($busqueda));

        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }


    public function obtener($id)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_planta(?)");
        $consulta->execute(array($id));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $resultado;
    }

    // =====================
    // ACTUALIZAR
    // =====================
    public function actualizar($id, $nombre_comun, $nombre_cientifico, $descripcion)
    {
        $consulta = $this->db->prepare("CALL sp_actualizar_planta(?, ?, ?, ?)");
        $resultado = $consulta->execute(array($id, $nombre_comun, $nombre_cientifico, $descripcion));
        $consulta->closeCursor();
        return $resultado;
    }

    // =====================
    // ELIMINAR
    // =====================
    public function eliminar($id)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_planta(?)");
        $resultado = $consulta->execute(array($id));
        $consulta->closeCursor();
        return $resultado;
    }
public function buscarPorNombreCientifico($nombre)
{
    $consulta = $this->db->prepare(
        "CALL sp_buscar_especimen_por_nombre(?, ?, ?)"
    );

    $consulta->execute(array(
        $nombre,
        0,
        100
    ));

    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado;
}

public function asociarEspecimen(
    $codigoEspecimen,
    $idPlanta
)
{
    $consulta = $this->db->prepare(
        "CALL sp_asociar_planta_especimen(?, ?)"
    );

    $resultado = $consulta->execute(
        array(
            $codigoEspecimen,
            $idPlanta
        )
    );

    $consulta->closeCursor();

    return $resultado;
}

public function verEspecimenesAsociados($nombreCientifico)
{
    $consulta = $this->db->prepare(
        "CALL sp_ver_especimenes_plantas(?)"
    );

    $consulta->execute(array($nombreCientifico));

    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado;
}

public function eliminarAsociacion($codigoEspecimen, $idPlanta)
{
    $consulta = $this->db->prepare(
        "CALL sp_eliminar_asociacion_planta(?, ?)"
    );

    $resultado = $consulta->execute(
        array(
            $codigoEspecimen,
            $idPlanta
        )
    );

    $consulta->closeCursor();

    return $resultado;
}
}