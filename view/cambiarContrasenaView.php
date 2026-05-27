<?php include_once "public/header.php" ?>

<div>
    <form action="?controlador=Usuario&accion=cambiarContrasena" method="post">
        <div>
            <input type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Nombre de usuario">
            <input type="password" name="contrasena" id="contrasena" placeholder="******">
            <input type="password" name="nuevaContrasena" id="nuevaContrasena" placeholder="******">
            <input type="password" name="confirmarContrasena" id="confirmarContrasena" placeholder="******">
        </div>
        <div>
            <input type="submit" value="Cambiar">
        </div>
    </form>
</div>

<?php include_once "public/footer.php" ?>