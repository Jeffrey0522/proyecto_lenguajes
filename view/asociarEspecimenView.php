<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$especimenes          = isset($especimenes) ? $especimenes : array();
$planta               = isset($planta) ? $planta : null;
$especimenesAsociados = isset($especimenesAsociados) ? $especimenesAsociados : array();
$idPlanta             = isset($idPlanta) ? $idPlanta : '';

// Helper compatible con PHP 5.6: devuelve el valor o el default si no existe.
if (!function_exists('_g')) {
    function _g($arr, $key, $default = '') {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
}
?>

<div class="page-header">
    <p class="page-eyebrow">Asociaciones</p>
    <h2>Asociar espécimen a planta</h2>
</div>

<?php if (isset($mensaje) && $mensaje != null) { ?>
    <div class="alerta alerta-<?php echo htmlspecialchars($tipoMensaje); ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php } ?>

<?php if ($planta) { ?>
    <div class="card panel-info">
        <h3>Planta seleccionada</h3>
        <p><strong>Nombre común:</strong> <?php echo htmlspecialchars(_g($planta, 'nombre_comun')); ?></p>
        <p><strong>Nombre científico:</strong> <em><?php echo htmlspecialchars(_g($planta, 'nombre_cientifico')); ?></em></p>
        <?php if (!empty($planta['descripcion'])) { ?>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($planta['descripcion']); ?></p>
        <?php } ?>
    </div>
<?php } ?>

<?php if (isset($especimenesAsociados) && count($especimenesAsociados) > 0) { ?>
<div class="card card-tabla">
    <h3>Especímenes asociados</h3>
    <table class="tabla-elegante">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre científico</th>
                <th>Nombre común</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($especimenesAsociados as $ea) { ?>
                <tr>
                    <td><span class="code-pill"><?php echo htmlspecialchars(_g($ea, 'codigo')); ?></span></td>
                    <td><em><?php echo htmlspecialchars(_g($ea, 'nombre_cientifico')); ?></em></td>
                    <td><?php echo htmlspecialchars(_g($ea, 'nombre_comun')); ?></td>
                    <td>
                        <form method="POST"
                              action="?controlador=Planta&accion=eliminarAsociacion"
                              style="display:inline;"
                              onsubmit="return confirm('¿Deseas eliminar esta asociación?');">
                            <input type="hidden" name="idPlanta" value="<?php echo htmlspecialchars($idPlanta); ?>">
                            <input type="hidden" name="codigoEspecimen"
                                   value="<?php echo htmlspecialchars(_g($ea, 'codigo')); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar asociación</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>

<div class="card">
    <h3>Buscar espécimen</h3>
    <form method="POST" action="?controlador=planta&accion=buscarEspecimen" class="form-inline">
        <input type="hidden" name="idPlanta" value="<?php echo htmlspecialchars($idPlanta); ?>">
        <input type="text"
               id="busqueda"
               name="busqueda"
               placeholder="Buscar por nombre científico"
               required>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
</div>

<?php if (isset($especimenes) && count($especimenes) > 0) { ?>
<div class="card card-tabla">
    <h3>Especímenes encontrados</h3>
    <table class="tabla-elegante">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre científico</th>
                <th>Nombre común</th>
                <th>Gabinete</th>
                <th>Gaveta</th>
                <th>Caja</th>
                <th>Vial</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($especimenes as $e) { ?>
                <tr>
                    <td><span class="code-pill"><?php echo htmlspecialchars(_g($e, 'codigo')); ?></span></td>
                    <td><em><?php echo htmlspecialchars(_g($e, 'nombre_cientifico')); ?></em></td>
                    <td><?php echo htmlspecialchars(_g($e, 'nombre_comun')); ?></td>
                    <td><?php echo htmlspecialchars(_g($e, 'gabinete', '—')); ?></td>
                    <td><?php echo htmlspecialchars(_g($e, 'gaveta', '—')); ?></td>
                    <td><?php echo htmlspecialchars(_g($e, 'caja', '—')); ?></td>
                    <td><?php echo htmlspecialchars(_g($e, 'vial', '—')); ?></td>
                    <td>
                        <form method="POST"
                              action="?controlador=Planta&accion=guardarAsociacion"
                              style="display:inline;">
                            <input type="hidden" name="idPlanta" value="<?php echo htmlspecialchars($idPlanta); ?>">
                            <input type="hidden" name="codigoEspecimen"
                                   value="<?php echo htmlspecialchars(_g($e, 'codigo')); ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Asociar</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>

<div class="acciones-pagina">
    <a href="?controlador=planta&accion=mostrar" class="btn btn-ghost">
        ← Volver a Plantas
    </a>
</div>

<?php include_once 'public/footer.php'; ?>
