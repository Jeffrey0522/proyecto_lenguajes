<?php include_once 'public/headerAdminContenido.php'; ?>

<div class="contenedor">
    <h2>Registro de Especimen</h2>

    <div class="card">
        <h3>Clasificacion taxonomica y Datos del especimen</h3>

        <form id="formRegistroEspecimen" method="post" enctype="multipart/form-data">
            <label>Codigo del Especimen:</label>
            <input type="text" name="codigo_especimen" placeholder="Ej: UCR-ENT-001" required>

            <label>Recolector:</label>
            <input type="text" name="recolector" placeholder="Nombre de quien recolecto">

            <label>Fecha de Recoleccion:</label>
            <input type="date" name="fecha_recoleccion">

            <br><br>
            <label>Orden:</label>
            <select id="orden" name="id_orden">
                <option value="">Seleccione un orden</option>
                <option value="SP">SP</option>
                <?php foreach ($ordenes as $orden) { ?>
                    <?php if (strtoupper($orden['nombre']) != 'SP') { ?>
                        <option value="<?php echo $orden['id']; ?>"><?php echo $orden['nombre']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>

            <label>Familia:</label>
            <select id="familia" name="id_familia" disabled>
                <option value="">Seleccione una familia</option>
            </select>

            <label>Subfamilia:</label>
            <select id="subfamilia" name="id_subfamilia" disabled>
                <option value="">Seleccione una subfamilia</option>
            </select>

            <label>Genero:</label>
            <select id="genero" name="id_genero" disabled>
                <option value="">Seleccione un genero</option>
            </select>

            <label>Especie:</label>
            <select id="especie" name="id_especie" disabled>
                <option value="">Seleccione una especie</option>
            </select>

            <br><br>
            <h3>Ubicacion fisica del especimen</h3>

            <label style="display:inline-block; margin-right: 15px;">
                <input type="radio" name="almacenamiento" value="gaveta" checked>
                Gaveta
            </label>
            <label style="display:inline-block;">
                <input type="radio" name="almacenamiento" value="vial">
                Vial
            </label>

            <div id="gavetaSelect" style="margin-top:15px;">
                <label>Gabinete:</label>
                <select id="gabinete" name="codigo_gabinete">
                    <option value="">Seleccione un gabinete</option>
                    <?php if (isset($gabinetes)) {
                        foreach ($gabinetes as $g) { ?>
                            <option value="<?php echo $g['codigo']; ?>">
                                <?php echo $g['codigo'] . ' - ' . $g['ubicacion']; ?>
                            </option>
                    <?php }
                    } ?>
                </select>

                <label>Gaveta:</label>
                <select id="gaveta" name="codigo_gaveta">
                    <option value="">Seleccione una gaveta</option>
                    <?php if (isset($gavetas)) {
                        foreach ($gavetas as $g) { ?>
                            <option value="<?php echo $g['codigo']; ?>" data-gabinete="<?php echo $g['codigo_gabinete']; ?>">
                                <?php echo $g['codigo'] . ' - ' . $g['descripcion']; ?>
                            </option>
                    <?php }
                    } ?>
                </select>
            </div>

            <div id="vialSelect" style="display:none; margin-top:15px;">
                <label>Caja:</label>
                <select id="caja" name="codigo_caja">
                    <option value="">Seleccione una caja</option>
                    <?php if (isset($cajas)) {
                        foreach ($cajas as $c) { ?>
                            <option value="<?php echo $c['codigo']; ?>">
                                <?php echo $c['codigo'] . ' - ' . $c['descripcion']; ?>
                            </option>
                    <?php }
                    } ?>
                </select>

                <label>Vial:</label>
                <select id="vial" name="codigo_vial">
                    <option value="">Seleccione un vial</option>
                    <?php if (isset($viales)) {
                        foreach ($viales as $v) { ?>
                            <option value="<?php echo $v['codigo']; ?>" data-caja="<?php echo $v['codigo_caja']; ?>">
                                <?php echo $v['codigo'] . ' - ' . $v['medio_conservacion']; ?>
                            </option>
                    <?php }
                    } ?>
                </select>
            </div>

            <label>Ubicacion Geografica (Direccion):</label>
            <input type="text" id="dir_texto" name="ubicacion_geografica" placeholder="Ej: Sendero Principal, Volcan Turrialba">

            <label>Latitud (Opcional):</label>
            <input type="text" id="lat" name="latitud" readonly>

            <label>Longitud (Opcional):</label>
            <input type="text" id="lng" name="longitud" readonly>

            <div id="map" style="height: 300px; width: 100%; margin-top: 15px; border-radius: 8px;"></div>

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

            <label>Notas adicionales:</label>
            <input type="text" name="notas" placeholder="Observaciones">

            <label>Subir imagenes:</label>
            <input type="file" name="imagenes[]" accept=".jpg,.png" multiple>

            <br><br>
            <button type="submit">Registrar especimen</button>
        </form>
    </div>

    <div class="card">
        <h3>Listado de especimenes registrados</h3>
        <table>
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Orden</th>
                    <th>Familia</th>
                    <th>Subfamilia</th>
                    <th>Genero</th>
                    <th>Especie</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($especimenes) && count($especimenes) > 0) {
                    foreach ($especimenes as $e) { ?>
                        <tr>
                            <td><input type="text" value="<?php echo $e['codigo']; ?>" readonly></td>
                            <td><input type="text" value="<?php echo $e['orden']; ?>" readonly></td>
                            <td><input type="text" value="<?php echo $e['familia']; ?>" readonly></td>
                            <td><input type="text" value="<?php echo $e['subfamilia']; ?>" readonly></td>
                            <td><input type="text" value="<?php echo $e['genero']; ?>" readonly></td>
                            <td><input type="text" value="<?php echo $e['especie']; ?>" readonly></td>
                            <td>
                                <button type="button" style="background: #f1c40f; padding: 8px 12px; margin-bottom: 5px; width: 100%; border:none; color:black; border-radius: 4px; cursor: pointer; font-weight: bold;"
                                    onclick="abrirCarrusel('<?php echo $e['codigo']; ?>')">
                                    Ver Fotos
                                </button>
                                <button type="button" style="background: #2ecc71; padding: 8px 12px; margin-bottom: 5px; width: 100%; border:none; color:white; border-radius: 4px; cursor: pointer; font-weight: bold;"
                                    onclick="abrirModalAgregarFotos('<?php echo $e['codigo']; ?>')">
                                    Anadir Fotos
                                </button>
                                <?php
                                $taxonomiaActualizar = array(
                                    'codigo' => $e['codigo'],
                                    'id_orden' => isset($e['id_orden']) ? $e['id_orden'] : '',
                                    'id_familia' => !empty($e['id_familia']) ? $e['id_familia'] : 'SP',
                                    'id_subfamilia' => !empty($e['id_subfamilia']) ? $e['id_subfamilia'] : 'SP',
                                    'id_genero' => !empty($e['id_genero']) ? $e['id_genero'] : 'SP',
                                    'id_especie' => !empty($e['id_especie']) ? $e['id_especie'] : 'SP'
                                );
                                ?>
                                <button type="button" style="background: #3498db; padding: 8px 12px; margin-bottom: 5px; width: 100%;"
                                    onclick='abrirModalActualizar(<?php echo htmlspecialchars(json_encode($taxonomiaActualizar), ENT_QUOTES, "UTF-8"); ?>)'>
                                    Actualizar
                                </button>
                                <button type="button" style="background: #e74c3c; padding: 8px 12px; width: 100%; border:none; color:white; border-radius: 4px; cursor: pointer;"
                                    onclick="abrirModalConfirmacion('<?php echo $e['codigo']; ?>')">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">No hay especimenes registrados en la base de datos.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'view/modals/registroMensaje.php'; ?>
<?php include 'view/modals/modalsEspecimen.php'; ?>

<script src="public/js/registroEspecimen.js"></script>

<?php include_once 'public/footer.php'; ?>
