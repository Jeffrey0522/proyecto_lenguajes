<?php

require_once 'model/EspecieModel.php';
require_once 'model/GeneroModel.php';

class EspecieController {

    private $model;
    private $generoModel;

    public function __construct() {

        $this->model = new EspecieModel();

        $this->generoModel = new GeneroModel();
    }

    public function mostrar() {

        $especies = $this->model->listarEspecies();

        $generos = $this->generoModel->listarGeneros();

        require_once 'view/especieView.php';
    }

    public function registrar() {

        $nombreCientifico = trim($_POST['nombre_cientifico']) !== '' ? trim($_POST['nombre_cientifico']) : null;

        $nombreComun = trim($_POST['nombre_comun']) !== '' ? trim($_POST['nombre_comun']) : null;

        $descripcion = trim($_POST['descripcion']) !== '' ? trim($_POST['descripcion']) : null;

        $idGenero = $_POST['id_genero'];

        $idUsuario = 1;

        if ($nombreCientifico === null && $nombreComun === null) {

            echo "<script>
                    localStorage.setItem('mensajeSistema','Debe ingresar al menos el nombre común o el nombre científico');
                    window.location='index.php?controlador=Especie&accion=mostrar';
                  </script>";

            return;
        }

        try {
            $this->model->insertarEspecie(
                $nombreCientifico,
                $nombreComun,
                $descripcion,
                $idGenero,
                $idUsuario
            );

            echo "<script>
                    localStorage.setItem('mensajeSistema','Especie registrada correctamente');
                    window.location='index.php?controlador=Especie&accion=mostrar';
                  </script>";

        } catch (Exception $e) {
            echo "<script>
                    localStorage.setItem('mensajeSistema','Error al registrar especie: " . addslashes($e->getMessage()) . "');
                    window.location='index.php?controlador=Especie&accion=mostrar';
                  </script>";
        }
    }

    public function buscar() {

        $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

        if ($busqueda == '') {

            $especies = $this->model->listarEspecies();

        } else {

            $especies = $this->model->buscarEspecies($busqueda);
        }

        $generos = $this->generoModel->listarGeneros();
        require_once 'view/especieView.php';
    }

    public function actualizar() {

        $id = $_POST['id'];
        $nombreCientifico = $_POST['nombre_cientifico'];
        $nombreComun = $_POST['nombre_comun'];
        $descripcion = $_POST['descripcion'];
        $idGenero = $_POST['id_genero'];

        if (trim($nombreCientifico) == '' && trim($nombreComun) == '') {
            echo "<script>
                   localStorage.setItem('mensajeSistema','Debe ingresar al menos el nombre común o el nombre científico');
                    window.location='index.php?controlador=Especie&accion=mostrar';
                  </script>";

            return;
        }
        $this->model->actualizarEspecie(
            $id,
            $nombreCientifico,
            $nombreComun,
            $descripcion,
            $idGenero
        );
        echo "<script>
                localStorage.setItem('mensajeSistema','Especie actualizada correctamente');
                window.location='index.php?controlador=Especie&accion=mostrar';
              </script>";
    }
    public function eliminar() {

        $id = $_GET['id'];

        $resultado = $this->model->eliminarEspecie($id);

        if ($resultado) {
            echo "<script>
                    localStorage.setItem('mensajeSistema','Especie eliminada correctamente');
                    window.location='index.php?controlador=Especie&accion=mostrar';
                  </script>";
        } else {
            echo "<script>
                    localStorage.setItem('mensajeSistema','No se puede eliminar esta especie porque tiene especímenes asociados');
                    window.location='index.php?controlador=Especie&accion=mostrar';
                  </script>";
        }
    }
}
?>