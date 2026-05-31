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
        $data['tipoMensaje'] = null;

        $this->view->show("gabineteView.php", $data);
    }

    public function registrar()
    {
        $gabinete = new gabineteModel();

        $codigo = trim(isset($_POST['codigo']) ? $_POST['codigo'] : "");
        $ubicacion = trim(isset($_POST['ubicacion']) ? $_POST['ubicacion'] : "");

        // temporal, después se toma de $_SESSION
        $idUsuario = 'Admin';

        if ($codigo != "" && $ubicacion != "") {
            try {
                if ($gabinete->existeGabinete($codigo)) {
                    $data['mensaje'] = "Ya existe un gabinete con ese cÃ³digo.";
                    $data['tipoMensaje'] = "error";
                } else {
                    $gabinete->registrar($codigo, $ubicacion, $idUsuario);
                    $data['mensaje'] = "Gabinete registrado correctamente.";
                    $data['tipoMensaje'] = "exito";
                }
            } catch (Exception $e) {
                $data['mensaje'] = "Error al registrar gabinete.";
                $data['tipoMensaje'] = "error";
            }
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
            $data['tipoMensaje'] = "error";
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
                $data['tipoMensaje'] = "exito";
            } catch (Exception $e) {
                $data['mensaje'] = $e->getMessage();
                $data['tipoMensaje'] = "error";
            }
        } else {
            $data['mensaje'] = "No se recibió el código del gabinete.";
            $data['tipoMensaje'] = "error";
        }

        $data['gabinetes'] = $gabinete->listar();
        $this->view->show("gabineteView.php", $data);
    }
}
