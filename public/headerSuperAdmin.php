<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SA-UCR Entomología</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="public/js/taxonomia.js"></script>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <h2><?php echo $_SESSION['nombreUsuario'] ?>  <?php echo $_SESSION['apellidoUsuario'] ?></h2>
            <a href="?controlador=Usuario&accion=vistaSuperAdmin">Inicio</a>
            <a href="?controlador=Usuario&accion=cerrarSesion">Cerrar sesion</a>
        </aside>
        <main class="contenido">