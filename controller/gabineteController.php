<?php

require_once 'model/gabineteModel.php';

class gabineteController
{

    public function __construct()
    {
        $this->view = new View();
    }

    public function mostrar()
    {
        $gabinete = new gabineteModel();

        $data['gabinetes'] = $gabinete->listar();
        $data['mensaje'] = null;

        $this->view->show("gabineteView.php", $data);
    }

    public function registrar()
    {
        $gabinete = new gabineteModel();

        $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;
        $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : null;

        // temporal, después se toma de $_SESSION
        $idUsuario = 'Admin';

        if ($codigo != null && $ubicacion != null && $codigo != "" && $ubicacion != "") {
            try {
                $gabinete->registrar($codigo, $ubicacion, $idUsuario);
                $data['mensaje'] = "Gabinete registrado correctamente.";
            } catch (Exception $e) {
                $data['mensaje'] = "Error al registrar gabinete.";
            }
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
        }

        $data['gabinetes'] = $gabinete->listar();
        $this->view->show("gabineteView.php", $data);
    }

    public function actualizar()
    {
        $gabinete = new gabineteModel();

        $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;
        $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : null;

        if ($codigo != null && $ubicacion != null && $ubicacion != "") {
            $gabinete->actualizar($codigo, $ubicacion);
            $data['mensaje'] = "Gabinete actualizado correctamente.";
        } else {
            $data['mensaje'] = "Debe completar la ubicación.";
        }

        $data['gabinetes'] = $gabinete->listar();
        $this->view->show("gabineteView.php", $data);
    }

    public function eliminar()
    {
        $gabinete = new gabineteModel();
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;

        if ($codigo != null) {
            try {
                $gabinete->eliminar($codigo);
                $data['mensaje'] = "Gabinete eliminado correctamente.";
            } catch (Exception $e) {
                $data['mensaje'] = "Error: " . $e->getMessage();
            }
        } else {
            $data['mensaje'] = "No se recibió el código del gabinete.";
        }

        $data['gabinetes'] = $gabinete->listar();
        $this->view->show("gabineteView.php", $data);
    }
}
