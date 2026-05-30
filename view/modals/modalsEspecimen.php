<div id="modalActualizar" class="modal-fondo" style="display:none;">
    <div class="modal-contenido" style="width: 400px; max-width: 95%;">
        <h3 style="color: #1E3A5F; margin-bottom: 15px;">Actualizar Taxonomía</h3>

        <div style="background: #9fbce4; color: #1E3A5F; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <p style="font-size: 16px; margin: 0;">Espécimen: <strong id="txtCodigoActualizar"></strong></p>
        </div>

        <form id="formActualizarTaxonomia" style="text-align: left;">
            <input type="hidden" id="mod_codigo" name="codigo_especimen">

            <label style="display: block; font-size: 14px; margin-bottom: 5px;">Orden:</label>
            <select id="mod_orden" name="id_orden" style="width: 100%; margin-bottom: 10px;" required>
                <option value="">Seleccione un orden</option>
                <option value="SP">SP</option>
                <?php foreach ($ordenes as $orden) { ?>
                    <?php if (strtoupper($orden['nombre']) != 'SP') { ?>
                        <option value="<?php echo $orden['id']; ?>"><?php echo $orden['nombre']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>

            <label style="display: block; font-size: 14px; margin-bottom: 5px;">Familia:</label>
            <select id="mod_familia" name="id_familia" style="width: 100%; margin-bottom: 10px;" disabled required>
                <option value="">Seleccione una familia</option>
            </select>

            <label style="display: block; font-size: 14px; margin-bottom: 5px;">Subfamilia:</label>
            <select id="mod_subfamilia" name="id_subfamilia" style="width: 100%; margin-bottom: 10px;" disabled>
                <option value="">Seleccione una subfamilia</option>
            </select>

            <label style="display: block; font-size: 14px; margin-bottom: 5px;">Género:</label>
            <select id="mod_genero" name="id_genero" style="width: 100%; margin-bottom: 10px;" disabled required>
                <option value="">Seleccione un género</option>
            </select>

            <label style="display: block; font-size: 14px; margin-bottom: 5px;">Especie:</label>
            <select id="mod_especie" name="id_especie" style="width: 100%; margin-bottom: 20px;" disabled required>
                <option value="">Seleccione una especie</option>
            </select>

            <button type="submit" style="background: #28a745; width: 100%; margin-bottom: 10px;">Guardar Cambios</button>
            <button type="button" style="background: #e74c3c; width: 100%;" onclick="cerrarModalActualizar()">Cancelar</button>
        </form>
    </div>
</div>

<div id="modalConfirmacion" class="modal-fondo" style="display:none;">
    <div class="modal-contenido" style="width: 350px; max-width: 90%;">
        <h3 style="color: #e74c3c; margin-bottom: 15px;">Confirmar Eliminación</h3>
        <p style="font-size: 15px; margin-bottom: 25px; color: #1E3A5F;">
            ¿Está seguro de que desea eliminar el espécimen <br><strong id="txtCodigoEliminar" style="font-size: 18px;"></strong>?
        </p>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" style="background: #e74c3c; width: 50%; padding: 10px;" onclick="ejecutarEliminacion()">Sí, eliminar</button>
            <button type="button" style="background: #1E3A5F; width: 50%; padding: 10px;" onclick="cerrarModalConfirmacion()">Cancelar</button>
        </div>
    </div>
</div>

<div id="modalCarrusel" class="modal-fondo" style="display:none;">
    <div class="modal-contenido" style="width: 500px; max-width: 95%;">
        <h3 style="color: #1E3A5F; margin-bottom: 10px;">Galería del Espécimen</h3>
        <p style="margin-bottom: 15px; background: #9fbce4; padding: 8px; border-radius: 5px;"><strong id="lblCodigoGaleria"></strong></p>

        <div style="position: relative; width: 100%; height: 350px; background: #000; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 15px;">
            <img id="imgCarrusel" src="" style="max-width: 100%; max-height: 100%; display: none;">
            <div id="loadingCarrusel" style="display: none; font-weight: bold; color: #fff;">Cargando foto...</div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <button type="button" id="btnPrevFoto" style="background: #1E3A5F; width: 30%; margin:0;" onclick="fotoAnterior()">Anterior</button>
            <span id="lblContadorFotos" style="font-weight: bold; color: #1E3A5F; font-size: 16px;">0 / 0</span>
            <button type="button" id="btnNextFoto" style="background: #1E3A5F; width: 30%; margin:0;" onclick="fotoSiguiente()">Siguiente</button>
        </div>

        <button type="button" style="background: #e74c3c; width: 100%; margin:0;" onclick="cerrarCarrusel()">Cerrar Galería</button>
    </div>
</div>

<div id="modalAgregarFotos" class="modal-fondo" style="display:none;">
    <div class="modal-contenido" style="width: 400px; max-width: 95%;">
        <h3 style="color: #1E3A5F; margin-bottom: 15px;">Agregar Nuevas Fotos</h3>

        <div style="background: #9fbce4; color: #1E3A5F; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <p style="font-size: 16px; margin: 0;">Espécimen: <strong id="txtCodigoAgregarFotos"></strong></p>
        </div>

        <form id="formAgregarFotos" style="text-align: left;" enctype="multipart/form-data">
            <input type="hidden" id="add_foto_codigo" name="codigo_especimen">

            <label style="display: block; font-size: 14px; margin-bottom: 5px;">Seleccione las imágenes:</label>
            <input type="file" name="nuevas_imagenes[]" accept=".jpg,.png" multiple required style="margin-bottom: 20px; width: 100%; padding: 10px; border: 1px dashed #ccc;">

            <button type="submit" style="background: #28a745; width: 100%; margin-bottom: 10px;">Subir Fotos</button>
            <button type="button" style="background: #e74c3c; width: 100%;" onclick="cerrarModalAgregarFotos()">Cancelar</button>
        </form>
    </div>
</div>