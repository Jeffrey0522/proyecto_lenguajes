<?php
include_once 'public/headerAdminContenido.php';
?>

<div class="contenedor">
    <h2>Gestión de Orden Taxonómico</h2>

    <div class="card">
        <h3>Registrar nuevo orden</h3>

        <form action="index.php?controlador=Orden&accion=registrar" method="POST">
            <label>Nombre del orden:</label>

            <input type="text"
                   name="nombre"
                   placeholder="Ingrese el nombre del orden"
                   required>

            <button type="submit">Guardar</button>
        </form>
    </div>

    <div class="card">
        <h3>Buscar orden</h3>

        <form action="index.php?controlador=Orden&accion=buscar" method="POST">
            <input type="text"
                   name="busqueda"
                   placeholder="Buscar por nombre parcial...">

            <button type="submit">Buscar</button>

            <a href="index.php?controlador=Orden&accion=mostrar">
                Ver todos
            </a>
        </form>
    </div>

    <div class="card">
        <h3>Listado de orden</h3>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($ordenes)) { ?>
                    <?php foreach ($ordenes as $orden) { ?>
                        <tr>
                            <form action="index.php?controlador=Orden&accion=actualizar" method="POST">
                                <input type="hidden"
                                       name="id"
                                       value="<?php echo $orden['id']; ?>">

                                <td>
                                    <input type="text"
                                           name="nombre"
                                           value="<?php echo $orden['nombre']; ?>"
                                           onchange="avisarCambio()"
                                           required>
                                </td>

                                <td>
                                    <button type="submit">Actualizar</button>

                                    <a href="index.php?controlador=Orden&accion=eliminar&id=<?php echo $orden['id']; ?>"
                                       onclick="return confirm('¿Seguro que desea eliminar este orden?');">
                                        Eliminar
                                    </a>
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="2">
                            No hay órdenes registradas.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'public/footer.php'; ?>