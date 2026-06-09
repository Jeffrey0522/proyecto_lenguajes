<?php include_once 'public/headerAdminContenido.php'; ?>
<?php

$especimenes = isset($especimenes)
    ? $especimenes
    : array();

$planta = isset($planta)
    ? $planta
    : null;

$especimenesAsociados = isset($especimenesAsociados)
    ? $especimenesAsociados
    : array();

?>

<h2>Asociar Especímen a Planta</h2>

<?php if (isset($mensaje) && $mensaje != null) { ?>
    <div class="<?php echo $tipoMensaje; ?>" style="padding: 10px; margin-bottom: 20px; border: 1px solid #ccc;">
        <?php echo $mensaje; ?>
    </div>
<?php } ?>

<?php if ($planta) { ?>
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; background-color: #f9f9f9;">
        <h3>Planta Seleccionada</h3>
        <p><strong>Nombre Común:</strong> <?php echo $planta['nombre_comun']; ?></p>
        <p><strong>Nombre Científico:</strong> <?php echo $planta['nombre_cientifico']; ?></p>
        <?php if ($planta['descripcion']) { ?>
            <p><strong>Descripción:</strong> <?php echo $planta['descripcion']; ?></p>
        <?php } ?>
    </div>
<?php } ?>
<?php if(isset($especimenesAsociados) && count($especimenesAsociados) > 0){ ?>

<h3>Especímenes Asociados</h3>

<table border="1">

    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre Científico</th>
            <th>Nombre Común</th>
            <th>Acción</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($especimenesAsociados as $ea){ ?>

        <tr>

            <td><?php echo $ea['codigo']; ?></td>

            <td><?php echo $ea['nombre_cientifico']; ?></td>

            <td><?php echo $ea['nombre_comun']; ?></td>

            <td>

                <form method="POST"
                      action="?controlador=Planta&accion=eliminarAsociacion"
                      style="display:inline;"
                      onsubmit="return confirm('¿Deseas eliminar esta asociación?');">

                    <input type="hidden"
                           name="idPlanta"
                           value="<?php echo $idPlanta; ?>">

                    <input type="hidden"
                           name="codigoEspecimen"
                           value="<?php echo isset($ea['codigo']) ? $ea['codigo'] : ''; ?>">

                    <button type="submit">
                        Eliminar Asociación
                    </button>

                </form>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php } ?>

<form method="POST"
      action="?controlador=planta&accion=buscarEspecimen">

    <input type="hidden"
           name="idPlanta"
           value="<?php echo $idPlanta; ?>">

    <label for="busqueda">Buscar Especímen por Nombre Científico:</label><br>
    <input type="text"
           id="busqueda"
           name="busqueda"
           placeholder="Ingrese el nombre científico"
           required>

    <button type="submit">
        Buscar
    </button>

</form>
<?php if(isset($especimenes) && count($especimenes) > 0){ ?>

<h3>Especímenes Encontrados</h3>

<table border="1" style="width: 100%; margin-top: 20px;">

    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre Científico</th>
            <th>Nombre Común</th>
            <th>Gabinete</th>
            <th>Gaveta</th>
            <th>Caja</th>
            <th>Vial</th>
            <th>Acción</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($especimenes as $e){ ?>

        <tr>

            <td><?php echo $e['codigo']; ?></td>

            <td><?php echo $e['nombre_cientifico']; ?></td>

            <td><?php echo $e['nombre_comun']; ?></td>

            <td><?php echo isset($e['gabinete']) ? $e['gabinete'] : '-'; ?></td>

            <td><?php echo isset($e['gaveta']) ? $e['gaveta'] : '-'; ?></td>

            <td><?php echo isset($e['caja']) ? $e['caja'] : '-'; ?></td>

            <td><?php echo isset($e['vial']) ? $e['vial'] : '-'; ?></td>

            <td>

                <form method="POST"
                      action="?controlador=Planta&accion=guardarAsociacion"
                      style="display: inline;">

                    <input type="hidden"
                           name="idPlanta"
                           value="<?php echo $idPlanta; ?>">

                    <input type="hidden"
                           name="codigoEspecimen"
                           value="<?php echo $e['codigo']; ?>">

                    <button type="submit">
                        Asociar
                    </button>

                </form>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php } ?>

<div style="margin-top: 20px;">
    <a href="?controlador=planta&accion=mostrar">
        <button type="button">Volver a Plantas</button>
    </a>
</div>

<?php include_once 'public/footer.php'; ?>