<?php

require_once 'model/FamiliaModel.php';
require_once 'model/OrdenModel.php';

class FamiliaController
{

    private $model;
    private $ordenModel;

    public function __construct()
    {
        $this->model = new FamiliaModel();
        $this->ordenModel = new OrdenModel();
    }

    public function mostrar()
    {
        session_start();
        if($_SESSION['username'] == null || $_SESSION['rol'] != '2'){
            header("Location: ?");
            exit();
        }
        $familias = $this->model->listarFamilias();
        $ordenes = $this->ordenModel->listarOrdenes();
        require_once 'view/familiaView.php';
        exit();
    }

    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $idOrden = $_POST['id_orden'];
        $idUsuario = 1;

        if ($this->model->existeFamilia($nombre)) {
            echo "<script>
                localStorage.setItem('mensajeSistema','Ya existe una familia con ese nombre');
                window.location='index.php?controlador=Familia&accion=mostrar';
              </script>";
            return;
        }

        $resultado = $this->model->insertarFamilia($nombre, $idOrden, $idUsuario);
        if ($resultado) {
            echo "<script>
                    localStorage.setItem('mensajeSistema','Familia registrada correctamente');
                    window.location='index.php?controlador=Familia&accion=mostrar';
                  </script>";
        } else {
            echo "<script>
                    localStorage.setItem('mensajeSistema','No se pudo registrar la familia');
                    window.location='index.php?controlador=Familia&accion=mostrar';
                  </script>";
        }
    }

    public function buscar()
    {
        $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

        if ($busqueda == '') {
            $familias = $this->model->listarFamilias();
        } else {
            $familias = $this->model->buscarFamilias($busqueda, 0);
        }

        $ordenes = $this->ordenModel->listarOrdenes();
        require_once 'view/familiaView.php';
    }

    public function actualizar()
    {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $idOrden = $_POST['id_orden'];
        $resultado = $this->model->actualizarFamilia($id, $nombre, $idOrden);

        if ($resultado) {
            echo "<script>
                    localStorage.setItem('mensajeSistema','Familia actualizada correctamente');
                    window.location='index.php?controlador=Familia&accion=mostrar';
                  </script>";
        } else {
            echo "<script>
                    localStorage.setItem('mensajeSistema','No se pudo actualizar la familia');
                    window.location='index.php?controlador=Familia&accion=mostrar';
                  </script>";
        }
    }

    public function eliminar()
    {
        $id = $_GET['id'];
        $resultado = $this->model->eliminarFamilia($id);

        if ($resultado) {
            echo "<script>
                    localStorage.setItem('mensajeSistema','Familia eliminada correctamente');
                    window.location='index.php?controlador=Familia&accion=mostrar';
                  </script>";
        } else {
            echo "<script>
                    localStorage.setItem('mensajeSistema','No se puede eliminar esta familia porque tiene subfamilias o géneros asociados');
                    window.location='index.php?controlador=Familia&accion=mostrar';
                  </script>";
        }
    }
}
