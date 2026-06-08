<?php

require_once 'model/bitacoraModel.php';

class bitacoraController
{
    public function __construct()
    {
        $this->view = new View();
    }

    public function mostrar()
    {
        $bitacora = new bitacoraModel();

        $desde = isset($_POST['desde']) ? $_POST['desde'] : null;
        $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : null;
        $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : "";

        $data['bitacoras'] = $bitacora->listar($desde, $hasta, $usuario);

        $data['desde'] = $desde;
        $data['hasta'] = $hasta;
        $data['usuario'] = $usuario;

        $this->view->show("bitacoraView.php", $data);
    }
}