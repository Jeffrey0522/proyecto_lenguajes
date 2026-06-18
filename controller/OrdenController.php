<?php

require_once 'model/OrdenModel.php';

class OrdenController
{

    private $model;

    public function __construct()
    {
        $this->model = new OrdenModel();
    }

    public function mostrar()
    {
        session_start();
        if($_SESSION['username'] == null || $_SESSION['rol'] != '2'){
            header("Location: ?");
            exit();
        }
        $ordenes = $this->model->listarOrdenes();
        require_once 'view/ordenView.php';
        exit();
    }

    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $idUsuario = 1;
        if ($this->model->existeOrden($nombre)) {

            echo "<script>
        localStorage.setItem(
            'mensajeSistema',
            'Ya existe un orden con ese nombre'
        );
        window.location='index.php?controlador=Orden&accion=mostrar';
    </script>";

            return;
        }

        $resultado = $this->model->insertarOrden($nombre, $idUsuario);

        if ($resultado) {
            echo "<script>
                   localStorage.setItem('mensajeSistema','Orden registrada correctamente');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        } else {
            echo "<script>
                   localStorage.setItem('mensajeSistema','No se pudo registrar la orden');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        }
    }

    public function buscar()
    {
        $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

        if ($busqueda == '') {
            $ordenes = $this->model->listarOrdenes();
        } else {
            $ordenes = $this->model->buscarOrden($busqueda);
        }

        require_once 'view/ordenView.php';
    }

    public function actualizar()
    {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];

        $resultado = $this->model->actualizarOrden($id, $nombre);

        if ($resultado) {
            echo "<script>
                   localStorage.setItem('mensajeSistema','Orden actualizada correctamente');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        } else {
            echo "<script>
                    localStorage.setItem('mensajeSistema','No se pudo actualizar la orden');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        }
    }

    public function eliminar()
    {
        $id = $_GET['id'];
        $resultado = $this->model->eliminarOrden($id);

        if ($resultado === true) {
            echo "<script>
                    localStorage.setItem('mensajeSistema','Orden eliminada correctamente');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        } else if ($resultado === 'familias') {
            echo "<script>
                   localStorage.setItem('mensajeSistema','No se puede eliminar este orden porque tiene familias asociadas');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        } else if ($resultado === 'subfamilias') {
            echo "<script>
                   localStorage.setItem('mensajeSistema','No se puede eliminar este orden porque tiene subfamilias asociadas');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        } else {
            echo "<script>
                   localStorage.setItem('mensajeSistema','No se puede eliminar este orden porque tiene familias asociadas');
                    window.location='index.php?controlador=Orden&accion=mostrar';
                  </script>";
        }
    }
}
