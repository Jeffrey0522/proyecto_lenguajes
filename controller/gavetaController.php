<?php

require_once 'model/gavetaModel.php';
require_once 'model/gabineteModel.php';

class gavetaController
{
    public function __construct()
    {
        $this->view = new View();
    }

    public function mostrar()
    {
        $gaveta = new gavetaModel();
        $gabinete = new gabineteModel();

        $data['gavetas'] = $gaveta->listar();
        $data['gabinetes'] = $gabinete->listar();
        $data['mensaje'] = null;

        $this->view->show("gavetaView.php", $data);
    }

    public function registrar()
    {
        $gaveta = new gavetaModel();
        $gabinete = new gabineteModel();

        $codigo = trim(isset($_POST['codigo']) ? $_POST['codigo'] : "");
        $descripcion = trim(isset($_POST['descripcion']) ? $_POST['descripcion'] : "");
        $codigoGabinete = trim(isset($_POST['codigo_gabinete']) ? $_POST['codigo_gabinete'] : "");

        $idUsuario = 'Admin';

        if ($codigo != "" && $descripcion != "" && $codigoGabinete != "") {

            if (!$gabinete->existeGabinete($codigoGabinete)) {

                $data['mensaje'] = "El gabinete seleccionado no existe.";
            } else if ($gaveta->existeCodigo($codigo)) {

                $data['mensaje'] = "Ya existe una gaveta con ese código.";
            } else {

                $gaveta->registrar($codigo, $descripcion, $codigoGabinete, $idUsuario);
                $data['mensaje'] = "Gaveta registrada correctamente.";
            }
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
        }

        $data['gavetas'] = $gaveta->listar();
        $data['gabinetes'] = $gabinete->listar();

        $this->view->show("gavetaView.php", $data);
    }

    public function actualizar()
    {
        $gaveta = new gavetaModel();
        $gabinete = new gabineteModel();

        $codigo = trim(isset($_POST['codigo']) ? $_POST['codigo'] : "");
        $descripcion = trim(isset($_POST['descripcion']) ? $_POST['descripcion'] : "");
        $codigoGabinete = isset($_POST['codigo_gabinete']) ? $_POST['codigo_gabinete'] : "";

        if ($codigo != "" && $descripcion != "" && $codigoGabinete != "") {
            $gaveta->actualizar($codigo, $descripcion, $codigoGabinete);
            $data['mensaje'] = "Gaveta actualizada correctamente.";
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
        }

        $data['gavetas'] = $gaveta->listar();
        $data['gabinetes'] = $gabinete->listar();

        $this->view->show("gavetaView.php", $data);
    }

    public function eliminar()
    {
        $gaveta = new gavetaModel();
        $gabinete = new gabineteModel();
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;

        if ($codigo != null) {
            try {
                $gaveta->eliminar($codigo);
                $data['mensaje'] = "Gaveta eliminada correctamente.";
            } catch (Exception $e) {
                $data['mensaje'] = "Error: " . $e->getMessage();
            }
        } else {
            $data['mensaje'] = "No se recibió el código de la gaveta.";
        }

        $data['gavetas'] = $gaveta->listar();
        $data['gabinetes'] = $gabinete->listar();
        $this->view->show("gavetaView.php", $data);
    }
}
