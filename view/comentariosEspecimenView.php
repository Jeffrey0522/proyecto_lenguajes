<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$comentarios     = isset($comentarios)     ? $comentarios     : array();
$codigoEspecimen = isset($codigoEspecimen) ? $codigoEspecimen : '';
?>

<div class="page-header">
    <p class="page-eyebrow">Moderación</p>
    <h2>Comentarios del espécimen <span class="code-pill"><?php echo htmlspecialchars($codigoEspecimen); ?></span></h2>
</div>

<?php if (isset($mensaje) && $mensaje != null) { ?>
    <div class="alerta alerta-<?php echo htmlspecialchars($tipoMensaje); ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php } ?>

<div class="card">
    <a href="?controlador=Comentario&accion=mostrar" class="btn btn-nav">&larr; Volver al listado</a>
</div>

<div class="card card-tabla">
    <?php if (empty($comentarios)) { ?>
        <p class="texto-mudo" style="padding:1rem;">Este espécimen no tiene comentarios activos.</p>
    <?php } else { ?>
        <table class="tabla-elegante">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Comentario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comentarios as $c) {
                    $id        = isset($c['id'])             ? $c['id']             : '';
                    $fecha     = isset($c['fecha'])          ? $c['fecha']          : '';
                    $nombre    = isset($c['nombre'])         ? $c['nombre']         : '';
                    $apellido  = isset($c['apellido'])       ? $c['apellido']       : '';
                    $cedula    = isset($c['cedula_usuario']) ? $c['cedula_usuario'] : '';
                    $texto     = isset($c['comentario'])     ? $c['comentario']     : '';
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fecha); ?></td>
                        <td>
                            <?php echo htmlspecialchars(trim($nombre . ' ' . $apellido)); ?>
                            <br><small class="texto-mudo"><?php echo htmlspecialchars($cedula); ?></small>
                        </td>
                        <td class="td-desc"><?php echo nl2br(htmlspecialchars($texto)); ?></td>
                        <td class="td-acciones">
                            <a class="btn btn-primary btn-sm"
                               style="background:#b03030;"
                               href="?controlador=Comentario&accion=eliminar&id=<?php echo (int)$id; ?>&codigo=<?php echo urlencode($codigoEspecimen); ?>"
                               onclick="return confirm('¿Eliminar este comentario? Esta acción no se puede deshacer.');">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<?php include_once 'public/footer.php'; ?>
