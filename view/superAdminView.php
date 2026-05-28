<?php
include_once 'public/headerSuperAdmin.php';
?>

<h1>Super Admin</h1>
<h2>Bienvenido <?php echo $_SESSION['nombreUsuario'] ?> <?php echo $_SESSION['apellidoUsuario']; ?> </h2>
<a href="?controlador=Usuario&accion=formularioCrearUsuario">Crear usuario</a>
<a href="?controlador=Usuario&accion=formularioCambiarContrasena">Cambiar contraseña</a>

<table>
    <thead>
        <tr>
            <th>Cedula</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Nombre de usuario</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vars['usuarios'] as $usuario) { ?>
            <tr>
                <td><?php echo $usuario['cedula']; ?></td>
                <td><?php echo $usuario['nombre']; ?></td>
                <td><?php echo $usuario['apellido']; ?></td>
                <td><?php echo $usuario['nombre_usuario']; ?></td>
                <td><?php echo $usuario['correo']; ?></td>
                <td><?php echo $usuario['rol']; ?></td>
                <td><?php echo $usuario['activo']; ?></td>
                <td>
                    <div>
                        <?php if ($usuario['activo'] == '1') { ?>
                            <form action="?controlador=Usuario&accion=eliminarUsuario" method="POST">
                                <input type="hidden" name="nombre_usuario" value="<?php echo $usuario['nombre_usuario'] ?>">
                                <input type="submit" value="Deshabilitar">
                            </form>
                        <?php } else { ?>
                            <form action="?controlador=Usuario&accion=habilitarUsuario" method="POST">
                                <input type="hidden" name="nombre_usuario" value="<?php echo $usuario['nombre_usuario'] ?>">
                                <input type="submit" value="Habilitar">
                            </form>
                        <?php } ?>
                        <form action="?controlador=Usuario&accion=formularioActualizar" method="POST">
                            <input type="hidden" name="cedula" value="<?php echo $usuario['cedula'] ?>">
                            <input type="submit" value="Editar">
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<a href="?controlador=Usuario&accion=cerrarSesion">Cerrar sesion</a>

<?php
include_once 'public/footer.php';
?>