<?php

require_once 'model/cajaModel.php';

class cajaController
{
    public function __construct()
    {
        $this->view = new View();
    }

    public function mostrar()
    {
        session_start();
        if($_SESSION['username'] == null || $_SESSION['rol'] != '2'){
            header("Location: ?");
            exit();
        }
        $caja = new cajaModel();

        $data['cajas'] = $caja->listar();
        $data['mensaje'] = null;
        $data['tipoMensaje'] = null;

        $this->view->show("cajaView.php", $data);
    }

    public function registrar()
    {
        $caja = new cajaModel();

        $codigo = trim(isset($_POST['codigo']) ? $_POST['codigo'] : "");
        $descripcion = trim(isset($_POST['descripcion']) ? $_POST['descripcion'] : "");
        $capacidad = trim(isset($_POST['capacidad']) ? $_POST['capacidad'] : "");

        $idUsuario = 'Admin';

        if ($codigo != "" && $descripcion != "" && $capacidad != "") {

            if (!is_numeric($capacidad) || $capacidad <= 0) {
                $data['mensaje'] = "La capacidad debe ser un número mayor a cero.";
                $data['tipoMensaje'] = "error";
            } else if ($caja->existeCodigo($codigo)) {
                $data['mensaje'] = "Ya existe una caja con ese código.";
                $data['tipoMensaje'] = "error";
            } else {
                $caja->registrar($codigo, $descripcion, $capacidad, $idUsuario);
                $data['mensaje'] = "Caja registrada correctamente.";
                $data['tipoMensaje'] = "exito";
            }
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
            $data['tipoMensaje'] = "error";
        }

        $data['cajas'] = $caja->listar();
        $this->view->show("cajaView.php", $data);
    }

    public function actualizar()
    {
        $caja = new cajaModel();

        $codigo = trim(isset($_POST['codigo']) ? $_POST['codigo'] : "");
        $descripcion = trim(isset($_POST['descripcion']) ? $_POST['descripcion'] : "");
        $capacidad = trim(isset($_POST['capacidad']) ? $_POST['capacidad'] : "");

        if ($codigo != "" && $descripcion != "" && $capacidad != "") {
            if (!is_numeric($capacidad) || $capacidad <= 0) {
                $data['mensaje'] = "La capacidad debe ser un número mayor a cero.";
                $data['tipoMensaje'] = "error";
            } else if (!$caja->capacidadValida($codigo, $capacidad)) {
                $data['mensaje'] = "La capacidad no puede ser menor que la cantidad de viales asociados.";
                $data['tipoMensaje'] = "error";
            } else {
                $caja->actualizar($codigo, $descripcion, $capacidad);
                $data['mensaje'] = "Caja actualizada correctamente.";
                $data['tipoMensaje'] = "exito";
            }
        } else {
            $data['mensaje'] = "Debe completar todos los campos.";
            $data['tipoMensaje'] = "error";
        }
        $data['cajas'] = $caja->listar();
        $this->view->show("cajaView.php", $data);
    }

    public function eliminar()
    {
        $caja = new cajaModel();
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;

        if ($codigo != null) {
            try {
                $caja->eliminar($codigo);
                $data['mensaje'] = "Caja eliminada correctamente.";
                $data['tipoMensaje'] = "exito";
            } catch (Exception $e) {
                $data['mensaje'] = $e->getMessage();
                $data['tipoMensaje'] = "error";
            }
        } else {
            $data['mensaje'] = "No se recibió el código de la caja.";
            $data['tipoMensaje'] = "error";
        }

        $data['cajas'] = $caja->listar();
        $this->view->show("cajaView.php", $data);
    }
}
