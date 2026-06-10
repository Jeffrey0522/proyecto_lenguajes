<?php include_once 'public/headerAdminContenido.php'; ?>

<?php
$plantas      = isset($plantas) ? $plantas : array();
$mostrarTabla = isset($mostrarTabla) ? $mostrarTabla : false;
$busqueda     = isset($busqueda) ? $busqueda : "";
?>

<div class="page-header">
    <p class="page-eyebrow">Catálogo</p>
    <h2>Gestión de plantas</h2>
</div>

<?php if (isset($mensaje) && $mensaje != null) { ?>
    <div class="alerta alerta-<?php echo htmlspecialchars($tipoMensaje); ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php } ?>

<div class="card">
    <h3>Registrar nueva planta</h3>
    <form method="POST" action="?controlador=planta&accion=registrar" class="form-grid">
        <div class="form-row">
            <label>Nombre común</label>
            <input type="text" name="nombre_comun" required>
        </div>
        <div class="form-row">
            <label>Nombre científico</label>
            <input type="text" name="nombre_cientifico" required>
        </div>
        <div class="form-row form-row-wide">
            <label>Descripción</label>
            <input type="text" name="descripcion" placeholder="Opcional">
        </div>
        <div class="form-row">
            <button type="submit" class="btn btn-primary">Registrar planta</button>
        </div>
    </form>
</div>

<div class="card">
    <h3>Buscar planta</h3>
    <form method="POST" action="?controlador=planta&accion=mostrar" class="form-inline">
        <input type="text"
               name="busqueda"
               placeholder="Buscar por nombre común o científico"
               value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
</div>

<?php if ($mostrarTabla) { ?>
    <div class="card card-tabla">
        <table class="tabla-elegante">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre común</th>
                    <th>Nombre científico</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plantas as $p) {
                    $id          = $p['id']                ?? '';
                    $nombreCom   = $p['nombre_comun']      ?? '';
                    $nombreCie   = $p['nombre_cientifico'] ?? '';
                    $descripcion = $p['descripcion']       ?? '';
                ?>
                <tr>
                    <td><span class="code-pill"><?php echo htmlspecialchars($id); ?></span></td>
                    <td><?php echo htmlspecialchars($nombreCom); ?></td>
                    <td><em><?php echo htmlspecialchars($nombreCie); ?></em></td>
                    <td class="td-desc"><?php echo $descripcion !== '' ? htmlspecialchars($descripcion) : '<span class="texto-mudo">—</span>'; ?></td>
                    <td class="td-acciones">
                        <form method="POST" action="?controlador=planta&accion=actualizar" class="form-acciones">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                            <input type="text" name="nombre_comun"
                                   value="<?php echo htmlspecialchars($nombreCom); ?>" required>
                            <input type="text" name="nombre_cientifico"
                                   value="<?php echo htmlspecialchars($nombreCie); ?>" required>
                            <input type="text" name="descripcion"
                                   value="<?php echo htmlspecialchars($descripcion); ?>"
                                   placeholder="Descripción">
                            <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                        </form>

                        <div class="acciones-extra">
                            <a class="link-asociar"
                               href="?controlador=planta&accion=asociarEspecimen&id=<?php echo urlencode($id); ?>">
                                Asociar espécimen
                            </a>
                            <a class="link-eliminar"
                               href="?controlador=planta&accion=eliminar&id=<?php echo urlencode($id); ?>"
                               onclick="return confirm('¿Eliminar esta planta?')">
                                Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<?php include_once 'public/footer.php'; ?>
