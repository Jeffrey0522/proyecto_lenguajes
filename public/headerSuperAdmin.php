<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="public/js/taxonomia.js"></script>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <h2><?php echo isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : 'Usuario'; ?> <?php echo isset($_SESSION['apellidoUsuario']) ? $_SESSION['apellidoUsuario'] : ''; ?></h2>
            <a href="?controlador=Usuario&accion=vistaSuperAdmin">Inicio</a>
            <a href="?controlador=bitacora&accion=mostrar" >Bitácora</a>   
            <a href="?controlador=Usuario&accion=cerrarSesion">Cerrar sesion</a>
        </aside>
        <main class="contenido">