<?php
include_once 'public/header.php';
?>

<h1>Super Admin</h1>
<h2>Bienvenido <?php echo $_SESSION['nombreUsuario'] ?> <?php echo $_SESSION['apellidoUsuario']; ?> <?php echo gettype($_SESSION['rol']); ?> </h2>
<a href="?controlador=Usuario&accion=formularioCrearUsuario">Crear usuario</a>
<a href="?controlador=Usuario&accion=cerrarSesion">Cerrar sesion</a>

<?php
include_once 'public/footer.php';
?>