<?php
/** @var array $generos */
?>
<?php include_once 'public/header.php'; ?>

<div class="contenedor">

    <h2>Gestión de Especie</h2>

    <div class="card">

        <h3>Registrar nueva especie</h3>

        <form action="index.php?controlador=Especie&accion=registrar"
              method="POST"
              onsubmit="return validarEspecie();">

            <label>Género:</label>

            <select name="id_genero" required>

                <option value="">
                    Seleccione un género
                </option>

                <?php foreach ($generos as $genero) { ?>

                    <?php if (strtoupper($genero['nombre']) != 'SP') { ?>

                        <option value="<?php echo $genero['id']; ?>">

                            <?php echo $genero['nombre']; ?>

                        </option>

                    <?php } ?>

                <?php } ?>

            </select>

            <br><br>

            <label>Nombre científico:</label>

            <input type="text"
                   name="nombre_cientifico"
                   placeholder="Ingrese el nombre científico">

            <br><br>

            <label>Nombre común:</label>

            <input type="text"
                   name="nombre_comun"
                   placeholder="Ingrese el nombre común">

            <br><br>

            <label>Descripción:</label>

            <input type="text"
                   name="descripcion"
                   placeholder="Ingrese una descripción">

            <br><br>

            <button type="submit">
                Guardar
            </button>

        </form>

    </div>

    <div class="card">

        <h3>Buscar especie</h3>

        <form action="index.php?controlador=Especie&accion=buscar" method="POST">

            <input type="text"
                   name="busqueda"
                   placeholder="Buscar por nombre científico o común...">

            <button type="submit">
                Buscar
            </button>

            <a href="index.php?controlador=Especie&accion=mostrar">
                Ver todas
            </a>

        </form>

    </div>

    <div class="card">

        <h3>Listado de especies</h3>

        <table border="1" cellpadding="8">

            <thead>

                <tr>

                    <th>Nombre científico</th>
                    <th>Nombre común</th>
                    <th>Descripción</th>
                    <th>Género</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

                <?php if (!empty($especies)) { ?>

                    <?php foreach ($especies as $especie) { ?>

                        <?php if (strtoupper($especie['nombre_cientifico']) == 'SP') { continue; } ?>

                        <tr>

                            <form action="index.php?controlador=Especie&accion=actualizar"
                                  method="POST">

                                <input type="hidden"
                                       name="id"
                                       value="<?php echo $especie['id']; ?>">

                                <td>

                                    <input type="text"
                                           name="nombre_cientifico"
                                           value="<?php echo $especie['nombre_cientifico']; ?>"
                                           onchange="avisarCambio()">

                                </td>

                                <td>

                                    <input type="text"
                                           name="nombre_comun"
                                           value="<?php echo $especie['nombre_comun']; ?>"
                                           onchange="avisarCambio()">

                                </td>

                                <td>

                                    <input type="text"
                                           name="descripcion"
                                           value="<?php echo $especie['descripcion']; ?>"
                                           onchange="avisarCambio()">

                                </td>

                                <td>

                                    <select name="id_genero"
                                            onchange="avisarCambio()"
                                            required>

                                        <option value="">
                                            Seleccione un género
                                        </option>

                                        <?php foreach ($generos as $genero) { ?>

                                            <?php if (strtoupper($genero['nombre']) != 'SP') { ?>

                                                <option value="<?php echo $genero['id']; ?>"
                                                    <?php echo ($especie['id_genero'] == $genero['id']) ? 'selected' : ''; ?>>

                                                    <?php echo $genero['nombre']; ?>

                                                </option>

                                            <?php } ?>

                                        <?php } ?>

                                    </select>

                                </td>

                                <td>

                                    <button type="submit">
                                        Actualizar
                                    </button>

                                    <a href="index.php?controlador=Especie&accion=eliminar&id=<?php echo $especie['id']; ?>"
                                       onclick="return confirm('¿Seguro que desea eliminar esta especie?');">

                                        Eliminar

                                    </a>

                                </td>

                            </form>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="5">
                            No hay especies registradas.
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php include_once 'public/footer.php'; ?>