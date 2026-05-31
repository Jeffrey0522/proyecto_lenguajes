<?php

require_once 'model/SubFamiliaModel.php';
require_once 'model/OrdenModel.php';
require_once 'model/FamiliaModel.php';

class SubFamiliaController
{

    private $model;
    private $ordenModel;
    private $familiaModel;

    public function __construct()
    {
        $this->model = new SubFamiliaModel();
        $this->ordenModel = new OrdenModel();
        $this->familiaModel = new FamiliaModel();
    }

    public function mostrar()
    {
        $subfamilias = $this->model->listarSubFamilias();
        $ordenes = $this->ordenModel->listarOrdenes();
        $familias = $this->familiaModel->listarFamilias();
        require_once 'view/subFamiliaView.php';
    }

    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $idOrden = $_POST['id_orden'];
        $idFamilia = $_POST['id_familia'];
        $idUsuario = 1;

        if ($this->model->existeSubFamilia($nombre)) {
            echo "<script>
                localStorage.setItem('mensajeSistema','Ya existe una subfamilia con ese nombre');
                window.location='index.php?controlador=SubFamilia&accion=mostrar';
              </script>";
            return;
        }

        $resultado = $this->model->insertarSubFamilia(
            $nombre,
            $idOrden,
            $idFamilia,
            $idUsuario
        );

        if ($resultado) {
            echo "<script>
               localStorage.setItem('mensajeSistema','Subfamilia registrada correctamente');
                window.location='index.php?controlador=SubFamilia&accion=mostrar';
              </script>";
        } else {
            echo "<script>
                localStorage.setItem('mensajeSistema','No se pudo registrar la subfamilia');
                window.location='index.php?controlador=SubFamilia&accion=mostrar';
              </script>";
        }
    }

    public function buscar()
    {

        $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

        if ($busqueda == '') {

            $subfamilias = $this->model->listarSubFamilias();
        } else {

            $subfamilias = $this->model->buscarSubFamilias(
                $busqueda,
                0,
                0
            );
        }

        $ordenes = $this->ordenModel->listarOrdenes();
        $familias = $this->familiaModel->listarFamilias();

        require_once 'view/subFamiliaView.php';
    }

    public function actualizar()
    {

        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $idOrden = $_POST['id_orden'];
        $idFamilia = $_POST['id_familia'];

        $resultado = $this->model->actualizarSubFamilia(
            $id,
            $nombre,
            $idOrden,
            $idFamilia
        );

        if ($resultado) {

            echo "<script>
                   localStorage.setItem('mensajeSistema','Subfamilia actualizada correctamente');
                    window.location='index.php?controlador=SubFamilia&accion=mostrar';
                  </script>";
        } else {

            echo "<script>
                   localStorage.setItem('mensajeSistema','No se pudo actualizar la subfamilia');
                    window.location='index.php?controlador=SubFamilia&accion=mostrar';
                  </script>";
        }
    }

    public function eliminar()
    {

        $id = $_GET['id'];

        $resultado = $this->model->eliminarSubFamilia($id);

        if ($resultado) {

            echo "<script>
                    localStorage.setItem('mensajeSistema','Subfamilia eliminada correctamente');
                    window.location='index.php?controlador=SubFamilia&accion=mostrar';
                  </script>";
        } else {

            echo "<script>
                    localStorage.setItem('mensajeSistema','No se puede eliminar esta subfamilia porque tiene géneros asociados');
                    window.location='index.php?controlador=SubFamilia&accion=mostrar';
                  </script>";
        }
    }
}
