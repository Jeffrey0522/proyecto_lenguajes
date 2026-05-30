<?php

require_once 'model/RegistroEspecimenModel.php';

class RegistroEspecimenController
{
    private $model;

    public function __construct()
    {
        $this->model = new RegistroEspecimenModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function mostrar()
    {
        $ordenes = $this->model->listarOrdenes();
        $gabinetes = $this->model->listarGabinetes();
        $gavetas = $this->model->listarGavetas();
        $cajas = $this->model->listarCajas();
        $viales = $this->model->listarViales();
        $especimenes = $this->model->listarEspecimenes();

        require_once 'view/registroEspecimenView.php';
    }

    public function obtenerFamiliasPorOrden()
    {
        $idOrden = isset($_GET['id_orden']) ? $_GET['id_orden'] : '';
        $datos = $this->model->obtenerFamiliasPorOrden($idOrden);
        echo json_encode($datos);
    }

    public function obtenerSubfamiliasPorFamilia()
    {
        $idFamilia = isset($_GET['id_familia']) ? $_GET['id_familia'] : '';
        $datos = $this->model->obtenerSubfamiliasPorFamilia($idFamilia);
        echo json_encode($datos);
    }

    public function obtenerGenerosPorSubfamilia()
    {
        $idSubFamilia = isset($_GET['id_sub_familia']) ? $_GET['id_sub_familia'] : '';
        $datos = $this->model->obtenerGenerosPorSubfamilia($idSubFamilia);
        echo json_encode($datos);
    }

    public function obtenerEspeciesPorGenero()
    {
        $idGenero = isset($_GET['id_genero']) ? $_GET['id_genero'] : '';
        $datos = $this->model->obtenerEspeciesPorGenero($idGenero);
        echo json_encode($datos);
    }

    // Registro de espécimen

    public function registrar()
    {
        try {
            $almacenamiento = isset($_POST['almacenamiento']) ? $_POST['almacenamiento'] : '';
            $codigo_gaveta = !empty($_POST['codigo_gaveta']) ? $_POST['codigo_gaveta'] : null;
            $codigo_vial   = !empty($_POST['codigo_vial']) ? $_POST['codigo_vial'] : null;

            if ($almacenamiento === 'gaveta') {
                $codigo_vial = null;
                if (!$codigo_gaveta) throw new Exception("Debe seleccionar una gaveta válida.");
            } elseif ($almacenamiento === 'vial') {
                $codigo_gaveta = null;
                if (!$codigo_vial) throw new Exception("Debe seleccionar un vial válido.");
            }

            $id_orden = (!empty($_POST['id_orden']) && $_POST['id_orden'] !== 'SP') ? $_POST['id_orden'] : null;
            $id_familia = (!empty($_POST['id_familia']) && $_POST['id_familia'] !== 'SP') ? $_POST['id_familia'] : null;
            $id_subfamilia = (!empty($_POST['id_sub_familia']) && $_POST['id_sub_familia'] !== 'SP') ? $_POST['id_sub_familia'] : null;
            $id_genero = (!empty($_POST['id_genero']) && $_POST['id_genero'] !== 'SP') ? $_POST['id_genero'] : null;
            $id_especie = !empty($_POST['id_especie']) ? $_POST['id_especie'] : null;
            $codigo_especimen = !empty($_POST['codigo_especimen']) ? trim($_POST['codigo_especimen']) : null;

            if (!$id_especie || !$codigo_especimen) {
                throw new Exception("El código del espécimen y la especie son obligatorios.");
            }

            $idUsuarioRegistro = isset($_SESSION['usuario_cedula']) ? $_SESSION['usuario_cedula'] : 1;

            if (empty($_POST['id_especie'])) {
                throw new Exception("Debe seleccionar una especie válida para continuar.");
            }

            $datos = array(
                'codigo_especimen'    => $codigo_especimen,
                'id_especie'          => $id_especie,
                'codigo_gaveta'       => $codigo_gaveta,
                'codigo_vial'         => $codigo_vial,
                'ubicacion_geografica' => !empty($_POST['ubicacion_geografica']) ? trim($_POST['ubicacion_geografica']) : null,
                'fecha_recoleccion'   => !empty($_POST['fecha_recoleccion']) ? $_POST['fecha_recoleccion'] : null,
                'recolector'          => !empty($_POST['recolector']) ? trim($_POST['recolector']) : null,
                'notas'               => !empty($_POST['notas']) ? trim($_POST['notas']) : null,
                'latitud'             => !empty($_POST['latitud']) ? $_POST['latitud'] : null,
                'longitud'            => !empty($_POST['longitud']) ? $_POST['longitud'] : null,
                'id_usuario_registro' => $idUsuarioRegistro
            );

            $this->model->registrarEspecimen($datos);

            if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
                if (!file_exists('uploads')) mkdir('uploads', 0777, true);
                foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
                    $ruta = 'uploads/' . time() . '_' . basename($_FILES['imagenes']['name'][$index]);
                    if (move_uploaded_file($tmpName, $ruta)) {
                        $this->model->asociarImagen($codigo_especimen, $ruta);
                    }
                }
            }

            echo json_encode(['success' => true, 'mensaje' => 'Espécimen registrado correctamente.']);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    public function actualizarTaxonomia()
    {
        try {
            $codigo = isset($_POST['codigo_especimen']) ? $_POST['codigo_especimen'] : null;
            $id_orden = isset($_POST['id_orden']) ? $_POST['id_orden'] : null;
            $id_familia = isset($_POST['id_familia']) ? $_POST['id_familia'] : null;
            $id_subfamilia = isset($_POST['id_subfamilia']) ? $_POST['id_subfamilia'] : null;
            $id_genero = isset($_POST['id_genero']) ? $_POST['id_genero'] : null;
            $id_especie = isset($_POST['id_especie']) ? $_POST['id_especie'] : null;

            if (!$codigo || !$id_especie) throw new Exception("Faltan datos obligatorios.");

            $idUsuario = isset($_SESSION['usuario_cedula']) ? $_SESSION['usuario_cedula'] : 1;

            if (!$id_especie) throw new Exception("El ID de la especie final no es válido.");
            if (empty($id_especie)) {
                throw new Exception("Debe seleccionar una especie válida registrada en el sistema.");
            }

            $this->model->actualizarTaxonomia($codigo, $id_especie);

            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    public function eliminar()
    {
        try {
            $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;
            if (!$codigo) throw new Exception("No se recibió el código del espécimen.");
            $this->model->eliminarEspecimen($codigo);
            echo json_encode(['success' => true, 'mensaje' => 'Espécimen eliminado correctamente.']);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    public function obtenerImagenCarrusel()
    {
        try {
            $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;
            $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
            if (!$codigo) throw new Exception("Código no proporcionado.");
            $resultado = $this->model->obtenerImagenCarrusel($codigo, $offset);
            if ($resultado) {
                echo json_encode(['success' => true, 'ruta' => $resultado['ruta_archivo'], 'total' => $resultado['total']]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No hay imágenes registradas.']);
            }
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    public function agregarImagenes()
    {
        try {
            $codigo_especimen = isset($_POST['codigo_especimen']) ? $_POST['codigo_especimen'] : null;
            if (!$codigo_especimen) throw new Exception("No se recibió el código del espécimen.");
            if (!isset($_FILES['nuevas_imagenes']) || empty($_FILES['nuevas_imagenes']['name'][0])) {
                throw new Exception("Debe seleccionar al menos una imagen.");
            }
            if (!file_exists('uploads')) mkdir('uploads', 0777, true);
            $imagenesAgregadas = 0;
            foreach ($_FILES['nuevas_imagenes']['tmp_name'] as $index => $tmpName) {
                $size = $_FILES['nuevas_imagenes']['size'][$index];
                $type = $_FILES['nuevas_imagenes']['type'][$index];
                if ($size > 5 * 1024 * 1024) continue;
                if (!in_array($type, array('image/jpeg', 'image/png'))) continue;
                $ruta = 'uploads/' . time() . '_' . basename($_FILES['nuevas_imagenes']['name'][$index]);
                if (move_uploaded_file($tmpName, $ruta)) {
                    $this->model->asociarImagen($codigo_especimen, $ruta);
                    $imagenesAgregadas++;
                }
            }
            if ($imagenesAgregadas > 0) {
                echo json_encode(['success' => true, 'mensaje' => "$imagenesAgregadas imagen(es) agregada(s) correctamente."]);
            } else {
                echo json_encode(['success' => false, 'error' => "No se pudo subir ninguna imagen."]);
            }
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}
