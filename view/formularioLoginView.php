<?php
include_once 'public/header.php';
?>

<section>
    <h1>Login</h1>
    <form action="?controlador=Usuario&accion=login" method="post">
        <div>
            <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de usuario" required>
            <input type="password" name="contrasena" id="contrasena" placeholder="******" required>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
</section>

<?php
include_once 'public/footer.php';
?>