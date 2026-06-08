<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$plantas = isset($plantas) ? $plantas : array();
$mostrarTabla = isset($mostrarTabla) ? $mostrarTabla : false;
$busqueda = isset($busqueda) ? $busqueda : "";
?>

<h2>Gestión de Plantas</h2>


<?php if (isset($mensaje) && $mensaje != null) { ?>
    <div class="<?php echo $tipoMensaje; ?>">
        <?php echo $mensaje; ?>
    </div>
<?php } ?>


<form method="POST" action="?controlador=planta&accion=registrar">

    <label>Nombre común:</label>
    <input type="text" name="nombre_comun" required>

    <label>Nombre científico:</label>
    <input type="text" name="nombre_cientifico" required>

    <label>Descripción:</label>
    <input type="text" name="descripcion">

    <button type="submit">Registrar Planta</button>
</form>

<br>

<form method="POST" action="?controlador=planta&accion=mostrar">

    <input type="text"
        name="busqueda"
        placeholder="Buscar por nombre común o científico"
        value="<?php echo $busqueda; ?>">

    <button type="submit">Buscar</button>
</form>

<br>


<?php if ($mostrarTabla) { ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre común</th>
                <th>Nombre científico</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($plantas as $p) { ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo $p['nombre_comun']; ?></td>
                    <td><?php echo $p['nombre_cientifico']; ?></td>
                    <td><?php echo $p['descripcion']; ?></td>

                    <td>

                        <form method="POST" action="?controlador=planta&accion=actualizar">

                            <input type="hidden"
                                name="id"
                                value="<?php echo $p['id']; ?>">

                            <input type="text"
                                name="nombre_comun"
                                value="<?php echo $p['nombre_comun']; ?>"
                                required>

                            <input type="text"
                                name="nombre_cientifico"
                                value="<?php echo $p['nombre_cientifico']; ?>"
                                required>

                            <input type="text"
                                name="descripcion"
                                value="<?php echo $p['descripcion']; ?>">

                            <button type="submit">
                                Actualizar
                            </button>

                        </form>

                        <br>

                        <a href="?controlador=planta&accion=eliminar&id=<?php echo $p['id']; ?>"
                            onclick="return confirm('¿Eliminar esta planta?')">
                            Eliminar
                        </a>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php } ?>

<?php include_once 'public/footer.php'; ?>