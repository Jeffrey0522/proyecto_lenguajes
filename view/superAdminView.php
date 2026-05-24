<?php
include_once 'public/header.php';
?>

<h1>Super Admin</h1>
<h2>Bienvenido <?php echo $_SESSION['nombreUsuario'] ?> <?php echo $_SESSION['apellidoUsuario']; ?> </h2>
<a href="?controlador=Usuario&accion=cerrarSesion">Cerrar sesion</a>

<?php
include_once 'public/footer.php';
?>