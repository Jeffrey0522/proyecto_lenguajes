<?php include_once 'public/header.php'; ?>

<?php
$cajas = isset($cajas) ? $cajas : array();
$mensaje = isset($mensaje) ? $mensaje : null;
?>

<h2>Gestión de Cajas</h2>

<?php include_once 'view/modals/registroMensaje.php'; ?>

<form method="POST" action="?controlador=caja&accion=registrar">
    <label>Código:</label>
    <input type="text" name="codigo" required>
    <label>Descripción:</label>
    <input type="text" name="descripcion" required>
    <label>Capacidad:</label>
    <input type="number" name="capacidad" min="1" required>
    <button type="submit">Registrar Caja</button>
</form>
<br>
<table border="1">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Capacidad</th>
            <th>Viales asociados</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cajas as $c) { ?>
            <tr>
                <td><?php echo $c['codigo']; ?></td>
                <td><?php echo $c['descripcion']; ?></td>
                <td><?php echo $c['capacidad']; ?></td>
                <td><?php echo $c['cantidad_viales']; ?></td>
                <td>
                    <form method="POST" action="?controlador=caja&accion=actualizar">
                        <input type="hidden" name="codigo" value="<?php echo $c['codigo']; ?>">
                        <input type="text"
                            name="descripcion"
                            value="<?php echo $c['descripcion']; ?>"
                            required>
                        <input type="number"
                            name="capacidad"
                            min="1"
                            value="<?php echo $c['capacidad']; ?>"
                            required>
                        <button type="submit">Editar</button>
                    </form>
                    <br>
                    <a href="?controlador=caja&accion=eliminar&codigo=<?php echo $c['codigo']; ?>">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<script>
    function cerrarModal() {
        document.getElementById("modalMensaje").style.display = "none";
    }
</script>
<?php include_once 'public/footer.php'; ?>