<?php

require_once 'model/RegistroTaxonomicoModel.php';

class RegistroTaxonomicoController {

    private $model;

    public function __construct() {
        $this->model = new RegistroTaxonomicoModel();
    }

    public function mostrar() {

        $ordenes = $this->model->listarOrdenes();

        require_once 'view/registroTaxonomicoView.php';
    }

    public function obtenerFamiliasPorOrden() {

        $idOrden = $_GET['id_orden'];

        $datos = $this->model->obtenerFamiliasPorOrden($idOrden);

        echo json_encode($datos);
    }

    public function obtenerSubfamiliasPorFamilia() {

        $idFamilia = $_GET['id_familia'];

        $datos = $this->model->obtenerSubfamiliasPorFamilia($idFamilia);

        echo json_encode($datos);
    }

    public function obtenerSubfamiliasPorOrden() {

        $idOrden = $_GET['id_orden'];

        $datos = $this->model->obtenerSubfamiliasPorOrden($idOrden);

        echo json_encode($datos);
    }

    public function obtenerGenerosPorFamilia() {

        $idFamilia = $_GET['id_familia'];

        $datos = $this->model->obtenerGenerosPorFamilia($idFamilia);

        echo json_encode($datos);
    }

    public function obtenerGenerosPorSubfamilia() {

        $idSubFamilia = $_GET['id_sub_familia'];

        $datos = $this->model->obtenerGenerosPorSubfamilia($idSubFamilia);

        echo json_encode($datos);
    }

    public function obtenerEspeciesPorGenero() {

        $idGenero = $_GET['id_genero'];

        $datos = $this->model->obtenerEspeciesPorGenero($idGenero);

        echo json_encode($datos);
    }
}
?>