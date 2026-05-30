<?php
/** @var array $ordenes */
?>
<?php
include_once 'public/headerAdminContenido.php';
?>

<div class="contenedor">

    <h2>Gestión de Familia</h2>

    <div class="card">

        <h3>Registrar nueva familia</h3>

        <form action="index.php?controlador=Familia&accion=registrar" method="POST">

            <label>Orden:</label>

            <select name="id_orden" required>

                <option value="">
                    Seleccione un orden
                </option>

                <?php foreach ($ordenes as $orden) { ?>

                    <?php if (strtoupper($orden['nombre']) != 'SP') { ?>

                        <option value="<?php echo $orden['id']; ?>">

                            <?php echo $orden['nombre']; ?>

                        </option>

                    <?php } ?>

                <?php } ?>

            </select>

            <br><br>

            <label>Nombre de la familia:</label>

            <input type="text"
                   name="nombre"
                   placeholder="Ingrese el nombre de la familia"
                   required>

            <button type="submit">Guardar</button>

        </form>

    </div>

    <div class="card">

        <h3>Buscar familia</h3>

        <form action="index.php?controlador=Familia&accion=buscar" method="POST">

            <input type="text"
                   name="busqueda"
                   placeholder="Buscar familia...">

            <button type="submit">Buscar</button>

            <a href="index.php?controlador=Familia&accion=mostrar">
                Ver todas
            </a>

        </form>

    </div>

    <div class="card">

        <h3>Listado de familia</h3>

        <table border="1" cellpadding="8">

            <thead>

                <tr>

                    <th>Familia</th>
                    <th>Orden</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

                <?php if (!empty($familias)) { ?>

                    <?php foreach ($familias as $familia) { ?>

                        <?php if (strtoupper($familia['nombre']) == 'SP') { continue; } ?>

                        <tr>

                            <form action="index.php?controlador=Familia&accion=actualizar"
                                  method="POST">

                                <input type="hidden"
                                       name="id"
                                       value="<?php echo $familia['id']; ?>">

                                <td>

                                    <input type="text"
                                           name="nombre"
                                           value="<?php echo $familia['nombre']; ?>"
                                           onchange="avisarCambio()"
                                           required>

                                </td>

                                <td>

                                    <select name="id_orden"
                                            onchange="avisarCambio()"
                                            required>

                                        <option value="">
                                            Seleccione un orden
                                        </option>

                                        <?php foreach ($ordenes as $orden) { ?>

                                            <?php if (strtoupper($orden['nombre']) != 'SP') { ?>

                                                <option value="<?php echo $orden['id']; ?>"

                                                    <?php
                                                    if ($familia['id_orden'] == $orden['id']) {
                                                        echo "selected";
                                                    }
                                                    ?>>

                                                    <?php echo $orden['nombre']; ?>

                                                </option>

                                            <?php } ?>

                                        <?php } ?>

                                    </select>

                                </td>

                                <td>

                                    <button type="submit">
                                        Actualizar
                                    </button>

                                    <a href="index.php?controlador=Familia&accion=eliminar&id=<?php echo $familia['id']; ?>"
                                       onclick="return confirm('¿Seguro que desea eliminar esta familia?');">

                                        Eliminar

                                    </a>

                                </td>

                            </form>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="3">
                            No hay familias registradas.
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php include_once 'public/footer.php'; ?>