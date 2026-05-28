<?php
/** @var array $familias */
/** @var array $ordenes */
?>
<?php include_once 'public/header.php'; ?>

<div class="contenedor">

    <h2>Gestión de Subfamilia</h2>

    <div class="card">

        <h3>Registrar nueva subfamilia</h3>

        <form action="index.php?controlador=SubFamilia&accion=registrar" method="POST">

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

            <label>Familia:</label>

            <select name="id_familia" required>

                <option value="">
                    Seleccione una familia
                </option>

                <?php foreach ($familias as $familia) { ?>

                    <?php if (strtoupper($familia['nombre']) != 'SP') { ?>

                        <option value="<?php echo $familia['id']; ?>">

                            <?php echo $familia['nombre']; ?>

                        </option>

                    <?php } ?>

                <?php } ?>

            </select>

            <br><br>

            <label>Nombre de la subfamilia:</label>

            <input type="text"
                   name="nombre"
                   placeholder="Ingrese el nombre de la subfamilia"
                   required>

            <button type="submit">Guardar</button>

        </form>

    </div>

    <div class="card">

        <h3>Buscar subfamilia</h3>

        <form action="index.php?controlador=SubFamilia&accion=buscar" method="POST">

            <input type="text"
                   name="busqueda"
                   placeholder="Buscar subfamilia...">

            <button type="submit">Buscar</button>

            <a href="index.php?controlador=SubFamilia&accion=mostrar">
                Ver todas
            </a>

        </form>

    </div>

    <div class="card">

        <h3>Listado de subfamilia</h3>

        <table border="1" cellpadding="8">

            <thead>

                <tr>

                    <th>Subfamilia</th>
                    <th>Orden</th>
                    <th>Familia</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

                <?php if (!empty($subfamilias)) { ?>

                    <?php foreach ($subfamilias as $subfamilia) { ?>

                        <tr>

                            <form action="index.php?controlador=SubFamilia&accion=actualizar"
                                  method="POST">

                                <input type="hidden"
                                       name="id"
                                       value="<?php echo $subfamilia['id']; ?>">

                                <td>

                                    <input type="text"
                                           name="nombre"
                                           value="<?php echo $subfamilia['nombre']; ?>"
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
                                                    <?php echo ($subfamilia['id_orden'] == $orden['id']) ? 'selected' : ''; ?>>

                                                    <?php echo $orden['nombre']; ?>

                                                </option>

                                            <?php } ?>

                                        <?php } ?>

                                    </select>

                                </td>

                                <td>

                                    <select name="id_familia"
                                            onchange="avisarCambio()"
                                            required>

                                        <option value="">
                                            Seleccione una familia
                                        </option>

                                        <?php foreach ($familias as $familia) { ?>

                                            <?php if (strtoupper($familia['nombre']) != 'SP') { ?>

                                                <option value="<?php echo $familia['id']; ?>"
                                                    <?php echo ($subfamilia['id_familia'] == $familia['id']) ? 'selected' : ''; ?>>

                                                    <?php echo $familia['nombre']; ?>

                                                </option>

                                            <?php } ?>

                                        <?php } ?>

                                    </select>

                                </td>

                                <td>

                                    <button type="submit">
                                        Actualizar
                                    </button>

                                    <a href="index.php?controlador=SubFamilia&accion=eliminar&id=<?php echo $subfamilia['id']; ?>"
                                       onclick="return confirm('¿Seguro que desea eliminar esta subfamilia?');">

                                        Eliminar

                                    </a>

                                </td>

                            </form>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="4">
                            No hay subfamilias registradas.
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php include_once 'public/footer.php'; ?>