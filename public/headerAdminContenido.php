<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SA-UCR Entomología</title>
    <?php $_v = @filemtime(__DIR__ . '/css/style.css'); ?>
    <link rel="stylesheet" href="public/css/style.css?v=<?php echo $_v; ?>">
    <link rel="stylesheet" href="public/css/gabinete.css?v=<?php echo $_v; ?>">
    <link rel="stylesheet" href="public/css/gaveta.css?v=<?php echo $_v; ?>">
    <link rel="stylesheet" href="public/css/caja.css?v=<?php echo $_v; ?>">
    <link rel="stylesheet" href="public/css/modal.css?v=<?php echo $_v; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="public/js/taxonomia.js?v=<?php echo $_v; ?>"></script>

</head>

<body>
    <!-- ================== Barra de navegación superior ================== -->
    <header>
        <nav class="top-nav">
            <div class="nav-logo">
                <a href="?controlador=Usuario&accion=vistaAdminContenido">SA-UCR Entomología</a>
            </div>
            <!-- Botones al lado derecho -->
            <div class="nav-buttons nav-right">
                <a href="index.php?controlador=Gabinete&accion=mostrar" class="btn-nav">Gabinete</a>
                <a href="index.php?controlador=Gaveta&accion=mostrar" class="btn-nav">Gaveta</a>
                <a href="index.php?controlador=Caja&accion=mostrar" class="btn-nav">Caja</a>
                <a href="index.php?controlador=Vial&accion=mostrar" class="btn-nav">Vial</a>

            </div>
        </nav>
    </header>

    <!-- ================== Layout principal ================== -->
    <div class="layout">
        <aside class="sidebar">
            <h2 class="titulo">Taxonomía</h2>
            <a href="?controlador=Usuario&accion=vistaAdminContenido">Inicio</a>
            <a href="index.php?controlador=Orden&accion=mostrar">Orden</a>
            <a href="index.php?controlador=Familia&accion=mostrar">Familia</a>
            <a href="index.php?controlador=SubFamilia&accion=mostrar">Subfamilia</a>
            <a href="index.php?controlador=Genero&accion=mostrar">Género</a>
            <a href="index.php?controlador=Especie&accion=mostrar">Especie</a>
            <a href="index.php?controlador=RegistroEspecimen&accion=mostrar">Registro Especimen</a>
            <a href="index.php?controlador=Planta&accion=mostrar" class="btn-nav">Registrar plantas</a>
            
            <a href="?controlador=Usuario&accion=cerrarSesion">Cerrar sesion</a>
        </aside>

        <main class="contenido">
            <!-- Aquí se cargará el contenido dinámico de cada vista -->