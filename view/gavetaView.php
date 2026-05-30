<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$gavetas = isset($gavetas) ? $gavetas : array();
$gabinetes = isset($gabinetes) ? $gabinetes : array();
$mensaje = isset($mensaje) ? $mensaje : null;
?>

<h2>Gestión de Gavetas</h2>

<?php include_once'view/modals/registroMensaje.php'; ?>

<form method="POST" action="?controlador=gaveta&accion=registrar">

    <label>Código:</label>
    <input type="text" name="codigo" required>

    <label>Descripción:</label>
    <input type="text" name="descripcion" required>

    <label>Gabinete:</label>
    <select name="codigo_gabinete" required>
        <option value="">Seleccione un gabinete</option>

        <?php foreach ($gabinetes as $gabinete) { ?>
            <option value="<?php echo $gabinete['codigo']; ?>">
                <?php echo $gabinete['codigo'] . " - " . $gabinete['ubicacion']; ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">
        Registrar Gaveta
    </button>

</form>

<br>

<table border="1">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Gabinete</th>
            <th>Cantidad de especímenes</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($gavetas as $g) { ?>
            <tr>
                <td><?php echo $g['codigo']; ?></td>
                <td><?php echo $g['descripcion']; ?></td>
                <td><?php echo $g['codigo_gabinete']; ?></td>
                <td><?php echo $g['cantidad_especimenes']; ?></td>

                <td>
                    <form method="POST" action="?controlador=gaveta&accion=actualizar">
                        <input type="hidden" name="codigo" value="<?php echo $g['codigo']; ?>">
                        <input type="text"
                            name="descripcion"
                            value="<?php echo $g['descripcion']; ?>"
                            required>
                        <select name="codigo_gabinete" required>
                            <?php foreach ($gabinetes as $gabinete) { ?>
                                <option value="<?php echo $gabinete['codigo']; ?>"
                                    <?php if ($gabinete['codigo'] == $g['codigo_gabinete']) {
                                        echo 'selected';
                                    } ?>>
                                    <?php echo $gabinete['codigo']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button type="submit">Editar</button>
                    </form>
                    <br>
                    <a href="?controlador=gaveta&accion=eliminar&codigo=<?php echo $g['codigo']; ?>">
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