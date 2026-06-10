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
        return $this->normalizarFilas($resultado);
    }


    public function obtener($id)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_planta(?)");
        $consulta->execute(array($id));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();
        return $this->normalizarFila($resultado);
    }

    /**
     * Normaliza una fila: pasa todas las keys a minúsculas y mapea
     * variantes con acento o nombres alternos a la forma canónica.
     * Así el view no depende del nombre exacto que devuelva el SP.
     */
    private function normalizarFila($fila)
    {
        if (!is_array($fila)) {
            return $fila;
        }

        $fila = array_change_key_case($fila, CASE_LOWER);

        $alias = array(
            'descripción'        => 'descripcion',
            'desc'               => 'descripcion',
            'nombre_común'       => 'nombre_comun',
            'nombrecomun'        => 'nombre_comun',
            'nombre_científico'  => 'nombre_cientifico',
            'nombrecientifico'   => 'nombre_cientifico'
        );
        foreach ($alias as $orig => $destino) {
            if (array_key_exists($orig, $fila) && !array_key_exists($destino, $fila)) {
                $fila[$destino] = $fila[$orig];
            }
        }

        return $fila;
    }

    private function normalizarFilas($filas)
    {
        if (!is_array($filas)) {
            return $filas;
        }
        $out = array();
        foreach ($filas as $fila) {
            $out[] = $this->normalizarFila($fila);
        }
        return $out;
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