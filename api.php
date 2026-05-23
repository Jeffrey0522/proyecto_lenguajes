<?php
// 1. Definir el encabezado para contenido JSON
header("Content-Type: application/json; charset=UTF-8");


$productos = [
    ["id" => 1, "nombre" => "Laptop", "precio" => 800],
    ["id" => 2, "nombre" => "Mouse", "precio" => 20],
    ["id" => 3, "nombre" => "Teclado", "precio" => 50]
];

// Respuesta según el método de petición
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {
    echo json_encode($productos);
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Solo se permite el método GET"]);
}
?>