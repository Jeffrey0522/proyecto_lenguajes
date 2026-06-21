<?php

require_once 'model/ComentarioModel.php';
require_once 'model/UsuarioModel.php';

class ComentarioController
{
    private $view;
    private $comentario;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->view = new View();
        $this->comentario = new ComentarioModel();
    }

    public function mostrar()
    {
        $this->protegerRuta('2');

        $busqueda = isset($_POST['busqueda']) ? trim($_POST['busqueda']) : "";

        $data['especimenes']  = $this->comentario->listarEspecimenesConComentarios($busqueda);
        $data['busqueda']     = $busqueda;
        $data['mensaje']      = isset($_SESSION['comentario_mensaje']) ? $_SESSION['comentario_mensaje'] : null;
        $data['tipoMensaje']  = isset($_SESSION['comentario_tipo'])    ? $_SESSION['comentario_tipo']    : null;
        unset($_SESSION['comentario_mensaje'], $_SESSION['comentario_tipo']);

        $this->view->show("comentariosAdminView.php", $data);
    }

    public function ver()
    {
        $this->protegerRuta('2');

        $codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : "";
        if ($codigo === "") {
            header("Location: ?controlador=Comentario&accion=mostrar");
            exit();
        }

        $data['codigoEspecimen'] = $codigo;
        $data['comentarios']     = $this->comentario->listarComentariosPorEspecimen($codigo);
        $data['mensaje']         = isset($_SESSION['comentario_mensaje']) ? $_SESSION['comentario_mensaje'] : null;
        $data['tipoMensaje']     = isset($_SESSION['comentario_tipo'])    ? $_SESSION['comentario_tipo']    : null;
        unset($_SESSION['comentario_mensaje'], $_SESSION['comentario_tipo']);

        $this->view->show("comentariosEspecimenView.php", $data);
    }

    public function eliminar()
    {
        $this->protegerRuta('2');

        $idComentario = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $codigo       = isset($_GET['codigo']) ? trim($_GET['codigo']) : "";

        if ($idComentario <= 0) {
            $_SESSION['comentario_mensaje'] = "ID de comentario inválido.";
            $_SESSION['comentario_tipo']    = "error";
            header("Location: ?controlador=Comentario&accion=mostrar");
            exit();
        }

        if ($codigo === "") {
            $codigo = $this->comentario->obtenerCodigoPorComentario($idComentario);
        }

        try {
            $usuario      = new UsuarioModel();
            $datosAdmin   = $usuario->buscarUsuario($_SESSION['username']);
            $cedulaAdmin  = isset($datosAdmin['cedula']) ? $datosAdmin['cedula'] : null;

            if (!$cedulaAdmin) {
                throw new Exception("No se pudo identificar al administrador.");
            }

            $this->comentario->eliminarComentario($idComentario, $cedulaAdmin);

            $_SESSION['comentario_mensaje'] = "Comentario eliminado correctamente.";
            $_SESSION['comentario_tipo']    = "exito";
        } catch (Exception $e) {
            $_SESSION['comentario_mensaje'] = "Error al eliminar el comentario.";
            $_SESSION['comentario_tipo']    = "error";
        }

        if ($codigo !== "" && $codigo !== null) {
            header("Location: ?controlador=Comentario&accion=ver&codigo=" . urlencode($codigo));
        } else {
            header("Location: ?controlador=Comentario&accion=mostrar");
        }
        exit();
    }

    private function protegerRuta($rolRequerido = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['username'])) {
            header("Location: ?");
            exit();
        }
        if ($rolRequerido !== null && $_SESSION['rol'] !== $rolRequerido) {
            header("Location: ?");
            exit();
        }
    }
}
