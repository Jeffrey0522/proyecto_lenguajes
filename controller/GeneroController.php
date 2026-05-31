<?php

require_once 'model/GeneroModel.php';
require_once 'model/FamiliaModel.php';
require_once 'model/SubFamiliaModel.php';

class GeneroController
{

    private $model;
    private $familiaModel;
    private $subFamiliaModel;

    public function __construct()
    {
        $this->model = new GeneroModel();
        $this->familiaModel = new FamiliaModel();
        $this->subFamiliaModel = new SubFamiliaModel();
    }

    public function mostrar()
    {
        $generos = $this->model->listarGeneros();
        $familias = $this->familiaModel->listarFamilias();
        $subfamilias = $this->subFamiliaModel->listarSubFamilias();
        require_once 'view/generoView.php';
    }

    public function registrar()
    {

        $nombre = $_POST['nombre'];
        $idFamilia = $_POST['id_familia'];
        $idSubFamilia = $_POST['id_sub_familia'];
        $idUsuario = 1;

        if ($this->model->existeGenero($nombre)) {
            echo "<script>
                localStorage.setItem('mensajeSistema','Ya existe un género con ese nombre');
                window.location='index.php?controlador=Genero&accion=mostrar';
              </script>";
            return;
        }

        $resultado = $this->model->insertarGenero(
            $nombre,
            $idFamilia,
            $idSubFamilia,
            $idUsuario
        );

        if ($resultado) {
            echo "<script>
                localStorage.setItem('mensajeSistema','Género registrado correctamente');
                window.location='index.php?controlador=Genero&accion=mostrar';
              </script>";
        } else {
            echo "<script>
                localStorage.setItem('mensajeSistema','No se pudo registrar el género');
                window.location='index.php?controlador=Genero&accion=mostrar';
              </script>";
        }
    }
    public function buscar()
    {

        $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

        if ($busqueda == '') {

            $generos = $this->model->listarGeneros();
        } else {

            $generos = $this->model->buscarGeneros($busqueda, 0, 0);
        }

        $familias = $this->familiaModel->listarFamilias();
        $subfamilias = $this->subFamiliaModel->listarSubFamilias();

        require_once 'view/generoView.php';
    }

    public function actualizar()
    {

        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $idFamilia = $_POST['id_familia'];
        $idSubFamilia = $_POST['id_sub_familia'];

        $this->model->actualizarGenero(
            $id,
            $nombre,
            $idFamilia,
            $idSubFamilia
        );

        echo "<script>
                localStorage.setItem('mensajeSistema','Género actualizado correctamente');
                window.location='index.php?controlador=Genero&accion=mostrar';
              </script>";
    }

    public function eliminar()
    {

        $id = $_GET['id'];

        $resultado = $this->model->eliminarGenero($id);

        if ($resultado) {

            echo "<script>
                  localStorage.setItem('mensajeSistema','Género eliminado correctamente');
                    window.location='index.php?controlador=Genero&accion=mostrar';
                  </script>";
        } else {

            echo "<script>
                   localStorage.setItem('mensajeSistema','No se puede eliminar este género porque tiene especies asociadas');
                    window.location='index.php?controlador=Genero&accion=mostrar';
                  </script>";
        }
    }
}
