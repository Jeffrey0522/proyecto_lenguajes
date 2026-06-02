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

<h3>Gabinetes</h3>

<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
    <?php foreach ($gabinetes as $gabinete) { ?>
        <button type="button"
            class="btn-gabinete"
            data-gabinete="<?php echo $gabinete['codigo']; ?>"
            onclick="mostrarGavetasPorGabinete('<?php echo $gabinete['codigo']; ?>')">
            <?php echo $gabinete['codigo']; ?>
        </button>
    <?php } ?>
</div>

<h3 id="tituloGavetas" style="display:none;">Gavetas del gabinete <span id="codigoGabineteSeleccionado"></span></h3>

<p id="mensajeSeleccionGabinete" style="font-weight: bold;">
    Seleccione un gabinete para ver sus gavetas.
</p>

<p id="mensajeSinGavetas" style="display:none; font-weight: bold;">
    Este gabinete no tiene gavetas registradas.
</p>

<table border="1" id="tablaGavetas" style="display:none;">
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
            <tr class="fila-gaveta" data-gabinete="<?php echo $g['codigo_gabinete']; ?>">
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

    function mostrarGavetasPorGabinete(codigoGabinete) {
        const tabla = document.getElementById("tablaGavetas");
        const filas = document.querySelectorAll(".fila-gaveta");
        const titulo = document.getElementById("tituloGavetas");
        const codigoSeleccionado = document.getElementById("codigoGabineteSeleccionado");
        const mensajeSeleccion = document.getElementById("mensajeSeleccionGabinete");
        const mensajeSinGavetas = document.getElementById("mensajeSinGavetas");
        const botones = document.querySelectorAll(".btn-gabinete");
        let cantidadVisible = 0;

        botones.forEach(function (boton) {
            const activo = boton.getAttribute("data-gabinete") === codigoGabinete;
            boton.style.background = activo ? "#1E3A5F" : "";
            boton.style.color = activo ? "#fff" : "";
        });

        filas.forEach(function (fila) {
            if (fila.getAttribute("data-gabinete") === codigoGabinete) {
                fila.style.display = "";
                cantidadVisible++;
            } else {
                fila.style.display = "none";
            }
        });

        codigoSeleccionado.innerText = codigoGabinete;
        titulo.style.display = "block";
        mensajeSeleccion.style.display = "none";
        tabla.style.display = cantidadVisible > 0 ? "table" : "none";
        mensajeSinGavetas.style.display = cantidadVisible > 0 ? "none" : "block";
    }
</script>

<?php include_once 'public/footer.php'; ?>