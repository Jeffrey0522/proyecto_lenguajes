<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$gabinetes = isset($gabinetes) ? $gabinetes : array();
$mensaje = isset($mensaje) ? $mensaje : null;
?>

<h2>Gestión de Gabinetes</h2>

<?php include 'view/modals/registroMensaje.php'; ?>

<form method="POST" action="?controlador=gabinete&accion=registrar">

    <label>Código:</label>
    <input type="text" name="codigo" required>
    <label>Ubicación:</label>
    <input type="text" name="ubicacion" required>
    <button type="submit"> Registrar Gabinete </button>
</form>
<br>
<table border="1">
    <thead>
        <tr>
            <th>Código</th>
            <th>Ubicación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($gabinetes as $g) { ?>
            <tr>
                <td> <?php echo $g['codigo']; ?> </td>
                <td> <?php echo $g['ubicacion']; ?> </td>
                <td>
                    <form method="POST"
                        action="?controlador=gabinete&accion=actualizar">
                        <input type="hidden" name="codigo" value="<?php echo $g['codigo']; ?>">
                        <input type="text" name="ubicacion" value="<?php echo $g['ubicacion']; ?>" required>
                        <button type="submit"> Editar </button>
                    </form>
                    <br>
                    <a href="?controlador=gabinete&accion=eliminar&codigo=<?php echo $g['codigo']; ?>">
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