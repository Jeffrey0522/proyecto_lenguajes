<?php
// Configuración de cabeceras para REST
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'libs/configuration.php';
require_once 'model/ProyectoModel.php';

$model = new ProyectoModel();
$metodo = $_SERVER['REQUEST_METHOD'];

// Obtener parámetros de la URL (si existen)
$nombreParam = isset($_GET['nombre']) ? $_GET['nombre'] : null;

switch ($metodo) {
    case 'GET':
        if ($nombreParam) {
            // Buscar un proyecto específico
            $resultado = $model->buscarProyecto($nombreParam);
            
            if ($resultado) {
                echo json_encode($resultado);
            } else {
                http_response_code(404);
                echo json_encode(["mensaje" => "Proyecto no encontrado"]);
            }
        } else {
            // Listar todos
            echo json_encode($model->listar());
        }
        break;

    case 'POST':
        // Leer el cuerpo de la petición (JSON)
        $datos = json_decode(file_get_contents("php://input"), true);

        if (!empty($datos['nombre']) && !empty($datos['fecha']) && !empty($datos['estado'])) {
            $model->registrarProyecto($datos['nombre'], $datos['fecha'], $datos['estado']);
            http_response_code(201);
            echo json_encode(["mensaje" => "Proyecto creado con éxito"]);
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Datos incompletos"]);
        }
        break;

    case 'PUT':
        // Actualizar requiere el nombre original y los nuevos datos
        $datos = json_decode(file_get_contents("php://input"), true);

        if (!empty($datos['nombreOriginal']) && !empty($datos['nombre'])) {
            $actualizado = $model->actualizarProyecto(
                $datos['nombreOriginal'], 
                $datos['nombre'], 
                $datos['fecha'], 
                $datos['estado']
            );
            
            if ($actualizado) {
                echo json_encode(["mensaje" => "Proyecto actualizado"]);
            } else {
                http_response_code(404);
                echo json_encode(["mensaje" => "No se pudo actualizar o no hubo cambios"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan parámetros para actualizar"]);
        }
        break;

    case 'DELETE':
        // Se espera el nombre por parámetro en la URL
        if ($nombreParam) {
            $eliminado = $model->eliminarProyecto($nombreParam);
            if ($eliminado) {
                echo json_encode(["mensaje" => "Proyecto eliminado"]);
            } else {
                http_response_code(404);
                echo json_encode(["mensaje" => "El proyecto no existe"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Se requiere el nombre del proyecto"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Método no permitido"]);
        break;
}