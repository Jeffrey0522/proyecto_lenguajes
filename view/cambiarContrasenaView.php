<?php
if ($_SESSION['rol'] == '1'){
    include_once "public/headerSuperAdmin.php";
} else {
    include_once "public/headerAdminContenido.php";
}
?>

<?php if (isset($_SESSION['cambiar_contrasena_error'])): ?>
    <div>
        <?= $_SESSION['cambiar_contrasena_error'];
        unset($_SESSION['cambiar_contrasena_error']); ?>
    </div>
<?php endif; ?>

<div>
    <form action="?controlador=Usuario&accion=cambiarContrasena" method="post">
        <div>
            <div>
                <label for="nombreUsuario">Nombre de Usuario:</label>
                <input type="text" name="nombreUsuario" id="nombreUsuario" value=<?php echo $_SESSION['username'] ?> readonly required>
            </div>
            <div>
                <label for="contrasena">Contraseña actual:</label>
                <input type="password" name="contrasena" id="contrasena" placeholder="******" required>
            </div>
            <div>
                <label for="nuevaContrasena">Nueva contraseña</label>
                <input type="password" name="nuevaContrasena" id="nuevaContrasena" placeholder="******" required>
            </div>
            <div>
                <label for="confirmarContrasena">Introduzca nuevamente la nueva contraseña</label>
                <input type="password" name="confirmarContrasena" id="confirmarContrasena" placeholder="******" required>
            </div>
        </div>
        <div>
            <input type="submit" value="Cambiar">
        </div>
    </form>
</div>

<?php include_once "public/footer.php" ?>