<?php
include_once 'public/headerSuperAdmin.php';
?>

<section>
    <h1>Actualizar usuario</h1>
    <form action="?controlador=Usuario&accion=actualizarUsuario" method="post">
        <div>
            <div>
                <label for="cedula">Digite su cedula:</label>
                <input type="text" name="cedula" id="cedula" placeholder="Cedula" value=<?php echo $vars['cedula'] ?> required readonly>
            </div>
            <div>
                <label for="nombre">Digite su nombre:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre" value=<?php echo $vars['nombre'] ?> required>
            </div>
            <div>
                <label for="apellido">Digite su apellido:</label>
                <input type="text" name="apellido" id="apellido" placeholder="Apellido" value=<?php echo $vars['apellido'] ?> required>
            </div>
            <div>
                <label for="email">Digite su email:</label>
                <input type="email" name="correo" id="correo" placeholder="Correo" value=<?php echo $vars['correo'] ?> required>
            </div>
            <div>
                <label for="nombre_usuario">Digite su nombre de usuario:</label>
                <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de Usuario" value=<?php echo $vars['nombre_usuario'] ?> required>
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
            <input type="submit" value="Actualizar">
        </div>
    </form>
</section>

<?php
include_once 'public/footer.php';
?>