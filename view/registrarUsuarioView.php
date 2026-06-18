<?php
include_once 'public/headerSuperAdmin.php';
?>

<?php if (isset($_SESSION['registrar_usuario'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['registrar_usuario'];
        unset($_SESSION['registrar_usuario']); ?>
    </div>
<?php endif; ?>

<section>
    <h1>Register</h1>
    <form action="?controlador=Usuario&accion=registrarUsuario" method="post">
        <div>
            <div>
                <label for="cedula">Digite su cedula:</label>
                <input type="text" name="cedula" id="cedula" placeholder="Cedula" required>
            </div>
            <div>
                <label for="nombre">Digite su nombre:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
            </div>
            <div>
                <label for="apellido">Digite su apellido:</label>
                <input type="text" name="apellido" id="apellido" placeholder="Apellido" required>
            </div>
            <div>
                <label for="email">Digite su email:</label>
                <input type="email" name="correo" id="correo" placeholder="Correo" required>
            </div>
            <div>
                <label for="nombre_usuario">Digite su nombre de usuario:</label>
                <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de Usuario" required>
            </div>
            <div>
                <label for="contrasena">Contraseña temporal:</label>
                <input type="text" name="contrasena" id="contrasena" value=<?php echo $vars['contrasenaGenerada'] ?> required readonly>
            </div>
            <div>
                <label for="rol">Selecione el rol del usuario</label>
                <select name="rol" id="rol">
                    <option value="1">Super administrador</option>
                    <option value="2">Administrador de contenido</option>
                    <option value="3">Usuario externo</option>
                </select>
            </div>
        </div>
        <div>
            <input type="submit" value="Registrar">
        </div>
    </form>
</section>

<?php
include_once 'public/footer.php';
?>