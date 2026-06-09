<?php

require_once 'model/PlantaModel.php';

class PlantaController
{
    public function __construct()
    {
        $this->view = new View();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function mostrar()
{
    $planta = new PlantaModel();

    $busqueda = isset($_POST['busqueda']) ? trim($_POST['busqueda']) : "";

    $data['mensaje'] = null;
    $data['tipoMensaje'] = null;

    if ($busqueda != "") {
        $data['plantas'] = $planta->listar($busqueda);
        $data['mostrarTabla'] = true;
    } else {
        $data['plantas'] = array();
        $data['mostrarTabla'] = false;
    }

    $data['busqueda'] = $busqueda;

    $this->view->show("plantaView.php", $data);
}

    // =====================
    // REGISTRAR
    // =====================
    public function registrar()
{
    $planta = new PlantaModel();

    $nombre_comun = isset($_POST['nombre_comun']) ? trim($_POST['nombre_comun']) : "";
    $nombre_cientifico = isset($_POST['nombre_cientifico']) ? trim($_POST['nombre_cientifico']) : "";
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";

    if ($nombre_comun != "" && $nombre_cientifico != "") {
        try {
            $planta->registrar($nombre_comun, $nombre_cientifico, $descripcion);
            $data['mensaje'] = "Planta registrada correctamente.";
            $data['tipoMensaje'] = "exito";
        } catch (Exception $e) {
            $data['mensaje'] = "Error: ya existe una planta con ese nombre científico.";
            $data['tipoMensaje'] = "error";
        }
    } else {
        $data['mensaje'] = "Nombre común y científico son obligatorios.";
        $data['tipoMensaje'] = "error";
    }

    $busqueda = "";
    $data['plantas'] = $planta->listar($busqueda);
    $data['busqueda'] = $busqueda;
    $data['mostrarTabla'] = true;
    $this->view->show("plantaView.php", $data);
}

public function actualizar()
{
    $planta = new PlantaModel();

    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $nombre_comun = isset($_POST['nombre_comun']) ? trim($_POST['nombre_comun']) : "";
    $nombre_cientifico = isset($_POST['nombre_cientifico']) ? trim($_POST['nombre_cientifico']) : "";
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";

    if ($id != "" && $nombre_comun != "" && $nombre_cientifico != "") {
        try {
            $planta->actualizar($id, $nombre_comun, $nombre_cientifico, $descripcion);
            $data['mensaje'] = "Planta actualizada correctamente.";
            $data['tipoMensaje'] = "exito";
        } catch (Exception $e) {
            $data['mensaje'] = "Error: ya existe una planta con ese nombre científico.";
            $data['tipoMensaje'] = "error";
        }
    } else {
        $data['mensaje'] = "Debe completar todos los campos obligatorios.";
        $data['tipoMensaje'] = "error";
    }

    $busqueda = "";
    $data['plantas'] = $planta->listar($busqueda);
    $data['busqueda'] = $busqueda;
    $data['mostrarTabla'] = true;
    $this->view->show("plantaView.php", $data);
}

    // =====================
    // ELIMINAR
    // =====================
    public function eliminar()
    {
        $planta = new PlantaModel();

        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id != null) {

            $planta->eliminar($id);

            $data['mensaje'] = "Planta eliminada correctamente.";
            $data['tipoMensaje'] = "exito";

        } else {
            $data['mensaje'] = "No se recibió el ID.";
            $data['tipoMensaje'] = "error";
        }

        $data['plantas'] = $planta->listar();
        $this->view->show("plantaView.php", $data);
    }

    public function asociarEspecimen()
{
    $planta = new PlantaModel();
    $idPlanta = $_GET['id'];

    $plantaData = $planta->obtener($idPlanta);
    $especimenesAsociados = $planta->verEspecimenesAsociados($plantaData['nombre_cientifico']);

    $data['idPlanta'] = $idPlanta;
    $data['planta'] = $plantaData;
    $data['especimenes'] = array();
    $data['especimenesAsociados'] = $especimenesAsociados;

    $this->view->show("asociarEspecimenView.php", $data);
}

public function buscarEspecimen()
{
    $planta = new PlantaModel();

    $busqueda = $_POST['busqueda'];
    $idPlanta = $_POST['idPlanta'];

    $plantaData = $planta->obtener($idPlanta);
    $especimenesAsociados = $planta->verEspecimenesAsociados($plantaData['nombre_cientifico']);

    $data['idPlanta'] = $idPlanta;
    $data['planta'] = $plantaData;
    $data['especimenes'] = $planta->buscarPorNombreCientifico($busqueda);
    $data['especimenesAsociados'] = $especimenesAsociados;

    $this->view->show("asociarEspecimenView.php", $data);
}

public function guardarAsociacion()
{
    $planta = new PlantaModel();

    $codigoEspecimen = $_POST['codigoEspecimen'];
    $idPlanta = $_POST['idPlanta'];

    $resultado = $planta->asociarEspecimen(
        $codigoEspecimen,
        $idPlanta
    );

    if ($resultado) {
        $data['mensaje'] =
            "Especímen asociado a la planta correctamente.";
        $data['tipoMensaje'] = "exito";
    } else {
        $data['mensaje'] =
            "Error al asociar el especímen.";
        $data['tipoMensaje'] = "error";
    }

    $plantaData = $planta->obtener($idPlanta);
    $especimenesAsociados = $planta->verEspecimenesAsociados($plantaData['nombre_cientifico']);

    $data['idPlanta'] = $idPlanta;
    $data['planta'] = $plantaData;
    $data['especimenes'] = array();
    $data['especimenesAsociados'] = $especimenesAsociados;

    $this->view->show(
        "asociarEspecimenView.php",
        $data
    );
}

public function eliminarAsociacion()
{
    $planta = new PlantaModel();

    $codigoEspecimen = $_POST['codigoEspecimen'];
    $idPlanta = $_POST['idPlanta'];

    $resultado = $planta->eliminarAsociacion(
        $codigoEspecimen,
        $idPlanta
    );

    if ($resultado) {
        $data['mensaje'] =
            "Asociación eliminada correctamente.";
        $data['tipoMensaje'] = "exito";
    } else {
        $data['mensaje'] =
            "Error al eliminar la asociación.";
        $data['tipoMensaje'] = "error";
    }

    $plantaData = $planta->obtener($idPlanta);
    $especimenesAsociados = $planta->verEspecimenesAsociados($plantaData['nombre_cientifico']);

    $data['idPlanta'] = $idPlanta;
    $data['planta'] = $plantaData;
    $data['especimenes'] = array();
    $data['especimenesAsociados'] = $especimenesAsociados;

    $this->view->show(
        "asociarEspecimenView.php",
        $data
    );
}
}