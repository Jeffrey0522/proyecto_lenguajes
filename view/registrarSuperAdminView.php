<?php
include_once 'public/header.php';
?>

<section>
    <h1>Register</h1>
    <form action="?controlador=Usuario&accion=registrarUsuario" method="post">
        <div>
            <input type="text" name="cedula" id="cedula" placeholder="Cedula" required>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" id="apellido" placeholder="Apellido" required>
            <input type="email" name="correo" id="correo" placeholder="Correo" required>
            <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="contrasena" id="contrasena" placeholder="******" required>
        </div>
        <div>
            <input type="submit" value="Registrar">
        </div>
    </form>
</section>

<?php
include_once 'public/footer.php';
?>