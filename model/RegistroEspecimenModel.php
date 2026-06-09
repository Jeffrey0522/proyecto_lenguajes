<?php

require_once 'libs/SPDO.php';

class RegistroEspecimenModel
{

    private $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    // Funciones de taxonomía

    public function listarOrdenes()
    {
        $consulta = $this->db->prepare("CALL sp_listar_ordenes()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerFamiliasPorOrden($idOrden)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_familias_por_orden(?)");
        $consulta->bindParam(1, $idOrden);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerSubfamiliasPorFamilia($idFamilia)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_subfamilias_por_familia(?)");
        $consulta->bindParam(1, $idFamilia);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerSubfamiliasPorOrden($idOrden)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_subfamilias_por_orden(?)");
        $consulta->bindParam(1, $idOrden);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerGenerosPorFamilia($idFamilia)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_generos_por_familia(?)");
        $consulta->bindParam(1, $idFamilia);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerGenerosPorSubfamilia($idSubFamilia)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_generos_por_subfamilia(?)");
        $consulta->bindParam(1, $idSubFamilia);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function obtenerEspeciesPorGenero($idGenero)
    {
        $consulta = $this->db->prepare("
            SELECT id, nombre_cientifico, nombre_comun
            FROM especies
            WHERE id_genero = ?
            ORDER BY nombre_cientifico, nombre_comun
        ");
        $consulta->bindParam(1, $idGenero);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    // Funciones de Registro

    public function registrarEspecimen($datos)
    {
        $consulta = $this->db->prepare(
            "CALL sp_registrar_especimen(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        try {
            $exito = $consulta->execute([
                $datos['codigo_especimen'],
                $datos['id_orden'],
                $datos['id_familia'],
                $datos['id_subfamilia'],
                $datos['id_genero'],
                $datos['id_especie'],
                $datos['codigo_gaveta'],
                $datos['codigo_vial'],
                $datos['ubicacion_geografica'],
                $datos['fecha_recoleccion'],
                $datos['recolector'],
                $datos['notas'],
                $datos['id_usuario_registro'],
                $datos['latitud'],
                $datos['longitud']
            ]);
        } catch (PDOException $e) {
            throw new Exception($this->limpiarMensajeRegistro($e->getMessage()));
        }

        if (!$exito) {
            $errorInfo = $consulta->errorInfo();
            throw new Exception($this->limpiarMensajeRegistro($errorInfo[2]));
        }
        $consulta->closeCursor();
    }

    private function limpiarMensajeRegistro($mensaje)
    {
        if (strpos($mensaje, '1062') !== false && strpos($mensaje, 'PRIMARY') !== false) {
            return "El codigo del especimen ya existe.";
        }

        return $mensaje;
    }

    public function asociarImagen($codigo_especimen, $ruta)
    {
        $consulta = $this->db->prepare(
            "CALL sp_asociar_imagen(?, ?)"
        );

        $exito = $consulta->execute([$codigo_especimen, $ruta]);

        if (!$exito) {
            $errorInfo = $consulta->errorInfo();
            throw new Exception("Error al guardar imagen: " . $errorInfo[2]);
        }

        $consulta->closeCursor();
    }

    // Funciones de Ubicación Física

    public function listarGabinetes()
    {
        $consulta = $this->db->prepare("CALL sp_listar_gabinetes()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function listarGavetas()
    {
        $consulta = $this->db->prepare("CALL sp_listar_gavetas()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function listarCajas()
    {
        $consulta = $this->db->prepare("CALL sp_listar_cajas()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function listarViales()
    {
        $consulta = $this->db->prepare("CALL sp_listar_viales()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function resolverTaxonomiaSP($id_orden, $id_familia, $id_subfamilia, $id_genero, $id_especie, $id_usuario)
    {

        $consulta = $this->db->prepare("CALL sp_resolver_taxonomia(?, ?, ?, ?, ?, ?, @out_id)");

        // Si el procedimiento falla internamente, lo atrapa
        if (!$consulta->execute([$id_orden, $id_familia, $id_subfamilia, $id_genero, $id_especie, $id_usuario])) {
            $errorInfo = $consulta->errorInfo();
            throw new Exception("Error en el Traductor Taxonómico: " . $errorInfo[2]);
        }
        $consulta->closeCursor();

        // Recuperar la variable de salida con el ID final
        $resultado = $this->db->prepare("SELECT @out_id AS id_especie_final");
        $resultado->execute();
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        // Validar que realmente haya devuelto un número
        if (!$fila || !$fila['id_especie_final']) {
            throw new Exception("El traductor no pudo generar o encontrar el ID para 'SP'. Verifica las llaves foráneas.");
        }

        return $fila['id_especie_final'];
    }

    public function listarEspecimenes()
    {
        $consulta = $this->db->prepare("CALL sp_listar_especimenes()");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function actualizarTaxonomia($codigo, $id_orden, $id_familia, $id_subfamilia, $id_genero, $id_especie)
    {
        $consulta = $this->db->prepare("CALL sp_actualizar_taxonomia_especimen(?, ?, ?, ?, ?, ?)");
        if (!$consulta->execute([$codigo, $id_orden, $id_familia, $id_subfamilia, $id_genero, $id_especie])) {
            $errorInfo = $consulta->errorInfo();
            throw new Exception("BD: " . $errorInfo[2]);
        }
        $consulta->closeCursor();
    }

    public function eliminarEspecimen($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_especimen(?)");
        if (!$consulta->execute([$codigo])) {
            $errorInfo = $consulta->errorInfo();
            throw new Exception("BD: " . $errorInfo[2]);
        }
        $consulta->closeCursor();
    }

    public function obtenerImagenCarrusel($codigo, $offset)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_imagen_carrusel(?, ?)");
        $consulta->execute([$codigo, $offset]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }
    // =====================
    // HU-17: Plantas hospedadoras
    // =====================
    public function listarPlantasEspecimen($codigo)
    {
        $consulta = $this->db->prepare("CALL sp_obtener_plantas_por_especimen(?)");
        $consulta->execute(array($codigo));
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function listarTodasLasPlantas()
    {
        $consulta = $this->db->prepare("CALL sp_listar_plantas(?)");
        $consulta->execute(array(""));
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $datos;
    }

    public function asociarPlanta($codigo, $idPlanta)
    {
        $consulta = $this->db->prepare("CALL sp_asociar_planta_especimen(?, ?)");
        $resultado = $consulta->execute(array($codigo, $idPlanta));
        $consulta->closeCursor();
        return $resultado;
    }

    public function eliminarPlantaEspecimen($codigo, $idPlanta)
    {
        $consulta = $this->db->prepare("CALL sp_eliminar_asociacion_planta(?, ?)");
        $resultado = $consulta->execute(array($codigo, $idPlanta));
        $consulta->closeCursor();
        return $resultado;
    }
} //class
