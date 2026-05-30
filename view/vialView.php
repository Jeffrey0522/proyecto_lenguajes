<?php include_once 'public/header.php'; ?>

<?php
$viales = isset($viales) ? $viales : array();
$cajas = isset($cajas) ? $cajas : array();
$mensaje = isset($mensaje) ? $mensaje : null;
?>

<h2>Gestión de Viales</h2>

<?php include_once 'view/modals/registroMensaje.php'; ?>

<form method="POST" action="?controlador=vial&accion=registrar">
    <label>Código:</label>
    <input type="text" name="codigo" required>
    <label>Medio de conservación:</label>
    <input type="text" name="medio_conservacion" required>
    <label>Caja:</label>
    <select name="codigo_caja" required>
        <option value="">Seleccione una caja</option>
        <?php foreach ($cajas as $caja) { ?>
            <option value="<?php echo $caja['codigo']; ?>">
                <?php echo $caja['codigo'] . " - " . $caja['descripcion']; ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit"> Registrar Vial </button>
</form>
<br>
<table border="1">
    <thead>
        <tr>
            <th>Código</th>
            <th>Medio de conservación</th>
            <th>Caja</th>
            <th>Descripción de caja</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($viales as $v) { ?>
            <tr>
                <td><?php echo $v['codigo']; ?></td>
                <td><?php echo $v['medio_conservacion']; ?></td>
                <td><?php echo $v['codigo_caja']; ?></td>
                <td><?php echo $v['descripcion_caja']; ?></td>
                <td>
                    <form method="POST" action="?controlador=vial&accion=actualizar">
                        <input type="hidden" name="codigo" value="<?php echo $v['codigo']; ?>">
                        <input type="text"
                            name="medio_conservacion"
                            value="<?php echo $v['medio_conservacion']; ?>"
                            required>
                        <select name="codigo_caja" required>
                            <?php foreach ($cajas as $caja) { ?>
                                <option value="<?php echo $caja['codigo']; ?>"
                                    <?php if ($caja['codigo'] == $v['codigo_caja']) {
                                        echo 'selected';
                                    } ?>>
                                    <?php echo $caja['codigo']; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <button type="submit"> Editar </button>
                    </form>
                    <br>
                    <a href="?controlador=vial&accion=eliminar&codigo=<?php echo $v['codigo']; ?>">
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