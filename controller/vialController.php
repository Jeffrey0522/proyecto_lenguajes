<?php

require_once 'model/vialModel.php';
require_once 'model/cajaModel.php';

class vialController
{
    public function __construct()
    {
        $this->view = new View();
    }

    public function mostrar()
    {
        $vial = new vialModel();
        $caja = new cajaModel();

        $data['viales'] = $vial->listar();
        $data['cajas'] = $caja->listar();
        $data['mensaje'] = null;

        $this->view->show("vialView.php", $data);
    }

    public function registrar()
    {
        $vial = new vialModel();
        $caja = new cajaModel();

        $codigo = trim(isset($_POST['codigo']) ? $_POST['codigo'] : "");
        $medioConservacion = trim(isset($_POST['medio_conservacion']) ? $_POST['medio_conservacion'] : "");
        $codigoCaja = trim(isset($_POST['codigo_caja']) ? $_POST['codigo_caja'] : "");

        $idUsuario = 'Admin';

        if ($codigo != "" && $medioConservacion != "" && $codigoCaja != "") {

            if (!$caja->existeCodigo($codigoCaja)) {
                $data['mensaje'] = "La caja seleccionada no existe.";
            } else if ($vial->existeCodigo($codigo)) {
                $data['mensaje'] = "Ya existe un vial con ese código.";
            } else if (!$caja->tieneCapacidadDisponible($codigoCaja)) {
                $data['mensaje'] = "La caja seleccionada ya alcanzó su capacidad máxima.";
            } else {
                $vial->registrar($codigo, $medioConservacion, $codigoCaja, $idUsuario);
                $data['mensaje'] = "Vial registrado correctamente.";
            }
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
        }

        $data['viales'] = $vial->listar();
        $data['cajas'] = $caja->listar();

        $this->view->show("vialView.php", $data);
    }

    public function actualizar()
    {
        $vial = new vialModel();
        $caja = new cajaModel();

        $codigo = trim(isset($_POST['codigo']) ? $_POST['codigo'] : "");
        $medioConservacion = trim(isset($_POST['medio_conservacion']) ? $_POST['medio_conservacion'] : "");
        $codigoCaja = trim(isset($_POST['codigo_caja']) ? $_POST['codigo_caja'] : "");

        if ($codigo != "" && $medioConservacion != "" && $codigoCaja != "") {

            if (!$caja->existeCodigo($codigoCaja)) {
                $data['mensaje'] = "La caja seleccionada no existe.";
            } else {
                $vial->actualizar($codigo, $medioConservacion, $codigoCaja);
                $data['mensaje'] = "Vial actualizado correctamente.";
            }
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
        }

        $data['viales'] = $vial->listar();
        $data['cajas'] = $caja->listar();

        $this->view->show("vialView.php", $data);
    }

    public function eliminar()
    {
        $vial = new vialModel();
        $caja = new cajaModel();
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;

        if ($codigo != null) {
            try {
                $vial->eliminar($codigo);
                $data['mensaje'] = "Vial eliminado correctamente.";
            } catch (Exception $e) {
                $data['mensaje'] = "Error: " . $e->getMessage();
            }
        } else {
            $data['mensaje'] = "No se recibió el código del vial.";
        }

        $data['viales'] = $vial->listar();
        $data['cajas'] = $caja->listar();
        $this->view->show("vialView.php", $data);
    }
}
