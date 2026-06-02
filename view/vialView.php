<?php include_once 'public/headerAdminContenido.php'; ?>

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

<h3>Cajas</h3>

<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
    <?php foreach ($cajas as $caja) { ?>
        <button type="button"
            class="btn-caja"
            data-caja="<?php echo $caja['codigo']; ?>"
            onclick="mostrarVialesPorCaja('<?php echo $caja['codigo']; ?>')">
            <?php echo $caja['codigo']; ?>
        </button>
    <?php } ?>
</div>

<h3 id="tituloViales" style="display:none;">Viales de la caja <span id="codigoCajaSeleccionada"></span></h3>

<p id="mensajeSeleccionCaja" style="font-weight: bold;">
    Seleccione una caja para ver sus viales.
</p>

<p id="mensajeSinViales" style="display:none; font-weight: bold;">
    Esta caja no tiene viales registrados.
</p>

<table border="1" id="tablaViales" style="display:none;">
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
            <tr class="fila-vial" data-caja="<?php echo $v['codigo_caja']; ?>">
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

    function mostrarVialesPorCaja(codigoCaja) {
        const tabla = document.getElementById("tablaViales");
        const filas = document.querySelectorAll(".fila-vial");
        const titulo = document.getElementById("tituloViales");
        const codigoSeleccionado = document.getElementById("codigoCajaSeleccionada");
        const mensajeSeleccion = document.getElementById("mensajeSeleccionCaja");
        const mensajeSinViales = document.getElementById("mensajeSinViales");
        const botones = document.querySelectorAll(".btn-caja");
        let cantidadVisible = 0;

        botones.forEach(function (boton) {
            const activo = boton.getAttribute("data-caja") === codigoCaja;
            boton.style.background = activo ? "#1E3A5F" : "";
            boton.style.color = activo ? "#fff" : "";
        });

        filas.forEach(function (fila) {
            if (fila.getAttribute("data-caja") === codigoCaja) {
                fila.style.display = "";
                cantidadVisible++;
            } else {
                fila.style.display = "none";
            }
        });

        codigoSeleccionado.innerText = codigoCaja;
        titulo.style.display = "block";
        mensajeSeleccion.style.display = "none";
        tabla.style.display = cantidadVisible > 0 ? "table" : "none";
        mensajeSinViales.style.display = cantidadVisible > 0 ? "none" : "block";
    }
</script>
<?php include_once 'public/footer.php'; ?>