<?php
/** @var array $familias */
/** @var array $subfamilias */
/** @var array $generos */
?>
<?php
include_once 'public/headerAdminContenido.php';
?>

<div class="contenedor">
    <h2>Gestión de Género</h2>

    <div class="card">
        <h3>Registrar nuevo género</h3>

        <form action="index.php?controlador=Genero&accion=registrar" method="POST">
            <label>Familia:</label>

            <select name="id_familia" required>
                <option value="">Seleccione una familia</option>

                <?php foreach ($familias as $familia) { ?>
                    <?php if (strtoupper($familia['nombre']) != 'SP') { ?>
                        <option value="<?php echo $familia['id']; ?>">
                            <?php echo $familia['nombre']; ?>
                        </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <br><br>

            <label>Subfamilia:</label>

            <select name="id_sub_familia" required>
                <option value="">Seleccione una subfamilia</option>

                <?php foreach ($subfamilias as $subfamilia) { ?>
                    <?php if (strtoupper($subfamilia['nombre']) != 'SP') { ?>
                        <option value="<?php echo $subfamilia['id']; ?>">
                            <?php echo $subfamilia['nombre']; ?>
                        </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <br><br>

            <label>Nombre del género:</label>

            <input type="text"
                   name="nombre"
                   placeholder="Ingrese el nombre del género"
                   required>

            <button type="submit">Guardar</button>
        </form>
    </div>

    <div class="card">
        <h3>Buscar género</h3>

        <form action="index.php?controlador=Genero&accion=buscar" method="POST">
            <input type="text"
                   name="busqueda"
                   placeholder="Buscar género...">

            <button type="submit">Buscar</button>

            <a href="index.php?controlador=Genero&accion=mostrar">
                Ver todos
            </a>
        </form>
    </div>

    <div class="card">
        <h3>Listado de género</h3>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Género</th>
                    <th>Familia</th>
                    <th>Subfamilia</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($generos)) { ?>
                    <?php foreach ($generos as $genero) { ?>
                        <tr>
                            <form action="index.php?controlador=Genero&accion=actualizar" method="POST">
                                <input type="hidden"
                                       name="id"
                                       value="<?php echo $genero['id']; ?>">

                                <td>
                                    <input type="text"
                                           name="nombre"
                                           value="<?php echo $genero['nombre']; ?>"
                                           onchange="avisarCambio()"
                                           required>
                                </td>

                                <td>
                                    <select name="id_familia"
                                            onchange="avisarCambio()"
                                            required>
                                        <option value="">Seleccione una familia</option>

                                        <?php foreach ($familias as $familia) { ?>
                                            <?php if (strtoupper($familia['nombre']) != 'SP') { ?>
                                                <option value="<?php echo $familia['id']; ?>"
                                                    <?php echo ($genero['id_familia'] == $familia['id']) ? 'selected' : ''; ?>>
                                                    <?php echo $familia['nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>

                                <td>
                                    <select name="id_sub_familia"
                                            onchange="avisarCambio()"
                                            required>
                                        <option value="">Seleccione una subfamilia</option>

                                        <?php foreach ($subfamilias as $subfamilia) { ?>
                                            <?php if (strtoupper($subfamilia['nombre']) != 'SP') { ?>
                                                <option value="<?php echo $subfamilia['id']; ?>"
                                                    <?php echo ($genero['id_sub_familia'] == $subfamilia['id']) ? 'selected' : ''; ?>>
                                                    <?php echo $subfamilia['nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>

                                <td>
                                    <button type="submit">
                                        Actualizar
                                    </button>

                                    <a href="index.php?controlador=Genero&accion=eliminar&id=<?php echo $genero['id']; ?>"
                                       onclick="return confirm('¿Seguro que desea eliminar este género?');">
                                        Eliminar
                                    </a>
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">
                            No hay géneros registrados.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'public/footer.php'; ?>