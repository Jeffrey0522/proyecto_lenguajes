<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$especimenes = isset($especimenes) ? $especimenes : array();
$busqueda    = isset($busqueda)    ? $busqueda    : "";
?>

<div class="page-header">
    <p class="page-eyebrow">Moderación</p>
    <h2>Comentarios por espécimen</h2>
</div>

<?php if (isset($mensaje) && $mensaje != null) { ?>
    <div class="alerta alerta-<?php echo htmlspecialchars($tipoMensaje); ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php } ?>

<div class="card">
    <h3>Buscar espécimen</h3>
    <form method="POST" action="?controlador=Comentario&accion=mostrar" class="form-inline">
        <input type="text"
               name="busqueda"
               placeholder="Código, nombre científico o común"
               value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="?controlador=Comentario&accion=mostrar" class="btn btn-nav">Limpiar</a>
    </form>
</div>

<div class="card card-tabla">
    <?php if (empty($especimenes)) { ?>
        <p class="texto-mudo" style="padding:1rem;">No hay especímenes con comentarios activos.</p>
    <?php } else { ?>
        <table class="tabla-elegante">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre científico</th>
                    <th>Nombre común</th>
                    <th>Comentarios</th>
                    <th>Último</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($especimenes as $e) {
                    $codigo   = isset($e['codigo'])               ? $e['codigo']               : '';
                    $cient    = isset($e['nombre_cientifico'])    ? $e['nombre_cientifico']    : '';
                    $comun    = isset($e['nombre_comun'])         ? $e['nombre_comun']         : '';
                    $cant     = isset($e['cantidad_comentarios']) ? $e['cantidad_comentarios'] : 0;
                    $ultimo   = isset($e['ultimo_comentario'])    ? $e['ultimo_comentario']    : '';
                ?>
                    <tr>
                        <td><span class="code-pill"><?php echo htmlspecialchars($codigo); ?></span></td>
                        <td><em><?php echo htmlspecialchars($cient); ?></em></td>
                        <td><?php echo htmlspecialchars($comun); ?></td>
                        <td><?php echo (int)$cant; ?></td>
                        <td><?php echo htmlspecialchars($ultimo); ?></td>
                        <td class="td-acciones">
                            <a class="btn btn-primary btn-sm"
                               href="?controlador=Comentario&accion=ver&codigo=<?php echo urlencode($codigo); ?>">
                                Ver comentarios
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<?php include_once 'public/footer.php'; ?>
