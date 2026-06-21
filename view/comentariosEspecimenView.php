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
                            <button type="button"
                                    class="btn btn-primary btn-sm"
                                    style="background:#b03030;"
                                    onclick="abrirModalEliminarComentario(<?php echo (int)$id; ?>, '<?php echo htmlspecialchars(addslashes(mb_substr($texto, 0, 80))); ?>')">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<!-- Modal de confirmación -->
<div id="modalEliminarComentario" class="modal" style="display:none;">
    <div class="modal-box">
        <h2>Eliminar comentario</h2>
        <div class="linea"></div>
        <p>
            ¿Está seguro que desea eliminar este comentario?<br>
            <em id="modalComentarioPreview" style="color:#4A6080;"></em><br>
            Esta acción no se puede deshacer.
        </p>
        <div class="modal-botones">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEliminarComentario()">
                Cancelar
            </button>
            <form id="modalEliminarForm" method="GET" action="index.php" style="width:50%;margin:0;">
                <input type="hidden" name="controlador" value="Comentario">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="id" id="modalEliminarId" value="">
                <input type="hidden" name="codigo" id="modalEliminarCodigo" value="">
                <button type="submit" class="btn-peligro" style="width:100%;">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const CODIGO_ESPECIMEN_ACTUAL = '<?php echo htmlspecialchars($codigoEspecimen, ENT_QUOTES); ?>';

    function abrirModalEliminarComentario(idComentario, previewTexto) {
        const modal   = document.getElementById('modalEliminarComentario');
        const preview = document.getElementById('modalComentarioPreview');

        document.getElementById('modalEliminarId').value     = idComentario;
        document.getElementById('modalEliminarCodigo').value = CODIGO_ESPECIMEN_ACTUAL;

        preview.textContent = previewTexto ? '“' + previewTexto + (previewTexto.length >= 80 ? '…' : '') + '”' : '';

        modal.style.display = 'flex';
    }

    function cerrarModalEliminarComentario() {
        document.getElementById('modalEliminarComentario').style.display = 'none';
    }

    document.getElementById('modalEliminarComentario').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEliminarComentario();
    });
</script>

<?php include_once 'public/footer.php'; ?>
