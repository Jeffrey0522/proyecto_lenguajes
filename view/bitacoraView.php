<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$bitacoras = isset($bitacoras) ? $bitacoras : array();
$desde = isset($desde) ? $desde : "";
$hasta = isset($hasta) ? $hasta : "";
$usuario = isset($usuario) ? $usuario : "";
?>

<h2>Bitácora de Actividad</h2>

<!-- FILTROS -->
<form method="POST" action="?controlador=bitacora&accion=mostrar">

    <label>Desde:</label>
    <input type="datetime-local" name="desde" value="<?php echo $desde; ?>">

    <label>Hasta:</label>
    <input type="datetime-local" name="hasta" value="<?php echo $hasta; ?>">

    <label>Usuario:</label>
    <input type="text" name="usuario" value="<?php echo $usuario; ?>" placeholder="Cédula usuario">

    <button type="submit">Filtrar</button>
</form>

<br>

<!-- TABLA -->
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Acción</th>
            <th>Tabla</th>
            <th>ID Registro</th>
            <th>Cédula Registro</th>
            <th>Fecha</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($bitacoras as $b) { ?>
            <tr>
                <td><?php echo $b['id']; ?></td>
                <td><?php echo $b['cedula_usuario']; ?></td>
                <td><?php echo $b['accion']; ?></td>
                <td><?php echo $b['tabla_afectada']; ?></td>
                <td><?php echo $b['id_registro']; ?></td>
                <td><?php echo $b['cedula_registro']; ?></td>
                <td><?php echo $b['fecha_creacion']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once 'public/footer.php'; ?>