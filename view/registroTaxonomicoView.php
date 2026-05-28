<?php

/** @var array $ordenes */
?>
<?php include_once 'public/header.php'; ?>

<div class="contenedor">
    <h2>Registro Taxonómico</h2>

    <div class="card">
        <h3>Clasificación taxonómica del espécimen</h3>

        <form onsubmit="return guardarClasificacion();">

            <label>Orden:</label>
            <select id="orden" aria-label="Seleccione un orden">
                <option value="">Seleccione un orden</option>
                <option value="SP">SP</option>
                <?php foreach ($ordenes as $orden) { ?>
                    <?php if (strtoupper($orden['nombre']) != 'SP') { ?>
                        <option value="<?php echo $orden['id']; ?>">
                            <?php echo $orden['nombre']; ?>
                        </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <br><br>

            <label>Familia:</label>
            <select id="familia" aria-label="Seleccione una familia" disabled>
                <option value="">Seleccione una familia</option>
            </select>

            <br><br>

            <label>Subfamilia:</label>
            <select id="subfamilia" aria-label="Seleccione una subfamilia" disabled>
                <option value="">Seleccione una subfamilia</option>
            </select>

            <br><br>

            <label>Género:</label>
            <select id="genero" aria-label="Seleccione un género" disabled>
                <option value="">Seleccione un género</option>
            </select>

            <br><br>

            <label>Especie:</label>
            <select id="especie" aria-label="Seleccione una especie" disabled>
                <option value="">Seleccione una especie</option>
            </select>

            <br><br>

            <label>Recolector:</label>
            <input type="text"
                id="recolector"
                placeholder="Nombre de quien recolectó"
                aria-label="Nombre de quien recolectó">

            <br><br>

            <div id="resumenTaxonomico" class="card">
                Seleccione una clasificación taxonómica.
            </div>

            <button type="submit">Guardar clasificación</button>
        </form>
    </div>
</div>

<script>
    const orden = document.getElementById('orden');
    const familia = document.getElementById('familia');
    const subfamilia = document.getElementById('subfamilia');
    const genero = document.getElementById('genero');
    const especie = document.getElementById('especie');
    const resumen = document.getElementById('resumenTaxonomico');

    function limpiarSelect(select, textoInicial) {
        select.innerHTML = '<option value="">' + textoInicial + '</option>';
    }

    function agregarSP(select) {
        let option = document.createElement('option');
        option.value = 'SP';
        option.textContent = 'SP';
        select.appendChild(option);
    }

    function agregarOpcion(select, value, text) {
        let option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        select.appendChild(option);
    }

    function textoSeleccionado(select) {
        if (select.disabled) {
            return 'SP';
        }

        if (!select.value) {
            return 'Sin seleccionar';
        }

        return select.options[select.selectedIndex].text;
    }

    function ponerSPYBloquearDesde(selectInicial) {
        if (selectInicial === familia) {
            limpiarSelect(familia, 'Seleccione una familia');
            agregarSP(familia);
            familia.value = 'SP';
            familia.disabled = true;
        }

        if (selectInicial === familia || selectInicial === subfamilia) {
            limpiarSelect(subfamilia, 'Seleccione una subfamilia');
            agregarSP(subfamilia);
            subfamilia.value = 'SP';
            subfamilia.disabled = true;
        }

        if (selectInicial === familia || selectInicial === subfamilia || selectInicial === genero) {
            limpiarSelect(genero, 'Seleccione un género');
            agregarSP(genero);
            genero.value = 'SP';
            genero.disabled = true;
        }

        limpiarSelect(especie, 'Seleccione una especie');
        agregarSP(especie);
        especie.value = 'SP';
        especie.disabled = true;

        actualizarResumen();
    }

    function actualizarResumen() {
        resumen.innerHTML =
            '<strong style="color:#1E3A5F;">Clasificación seleccionada:</strong><br><br>' +
            '<strong style="color:#1E3A5F;">Orden:</strong> ' + textoSeleccionado(orden) + '<br>' +
            '<strong style="color:#1E3A5F;">Familia:</strong> ' + textoSeleccionado(familia) + '<br>' +
            '<strong style="color:#1E3A5F;">Subfamilia:</strong> ' + textoSeleccionado(subfamilia) + '<br>' +
            '<strong style="color:#1E3A5F;">Género:</strong> ' + textoSeleccionado(genero) + '<br>' +
            '<strong style="color:#1E3A5F;">Especie:</strong> ' + textoSeleccionado(especie);
    }

    async function cargarFamiliasPorOrden() {
        limpiarSelect(familia, 'Seleccione una familia');
        agregarSP(familia);

        limpiarSelect(subfamilia, 'Seleccione una subfamilia');
        agregarSP(subfamilia);

        limpiarSelect(genero, 'Seleccione un género');
        agregarSP(genero);

        limpiarSelect(especie, 'Seleccione una especie');
        agregarSP(especie);

        familia.disabled = false;
        subfamilia.disabled = false;
        genero.disabled = true;
        especie.disabled = true;

        const response = await fetch(
            'index.php?controlador=RegistroTaxonomico&accion=obtenerFamiliasPorOrden&id_orden=' + orden.value
        );

        const datos = await response.json();

        datos.forEach(function(f) {
            agregarOpcion(familia, f.id, f.nombre);
        });

        await cargarSubfamiliasPorOrden();
        actualizarResumen();
    }

    async function cargarSubfamiliasPorOrden() {
        const response = await fetch(
            'index.php?controlador=RegistroTaxonomico&accion=obtenerSubfamiliasPorOrden&id_orden=' + orden.value
        );

        const datos = await response.json();

        limpiarSelect(subfamilia, 'Seleccione una subfamilia');
        agregarSP(subfamilia);

        limpiarSelect(genero, 'Seleccione un género');
        agregarSP(genero);

        limpiarSelect(especie, 'Seleccione una especie');
        agregarSP(especie);

        subfamilia.disabled = false;
        genero.disabled = true;
        especie.disabled = true;

        datos.forEach(function(sf) {
            agregarOpcion(subfamilia, sf.id, sf.nombre);
        });

        actualizarResumen();
    }

    async function cargarSubfamiliasPorFamilia() {
        const response = await fetch(
            'index.php?controlador=RegistroTaxonomico&accion=obtenerSubfamiliasPorFamilia&id_familia=' + familia.value
        );

        const datos = await response.json();

        limpiarSelect(subfamilia, 'Seleccione una subfamilia');
        agregarSP(subfamilia);

        limpiarSelect(genero, 'Seleccione un género');
        agregarSP(genero);

        limpiarSelect(especie, 'Seleccione una especie');
        agregarSP(especie);

        subfamilia.disabled = false;
        genero.disabled = true;
        especie.disabled = true;

        datos.forEach(function(sf) {
            agregarOpcion(subfamilia, sf.id, sf.nombre);
        });

        actualizarResumen();
    }
    async function cargarGenerosPorFamilia() {
    limpiarSelect(genero, 'Seleccione un género');
    agregarSP(genero);

    limpiarSelect(especie, 'Seleccione una especie');
    agregarSP(especie);

    genero.disabled = false;
    especie.disabled = true;

    const response = await fetch(
        'index.php?controlador=RegistroTaxonomico&accion=obtenerGenerosPorFamilia&id_familia=' + familia.value
    );

    const datos = await response.json();

    datos.forEach(function(g) {
        agregarOpcion(genero, g.id, g.nombre);
    });

    actualizarResumen();
}

    async function cargarGenerosPorSubfamilia() {
        limpiarSelect(genero, 'Seleccione un género');
        agregarSP(genero);

        limpiarSelect(especie, 'Seleccione una especie');
        agregarSP(especie);

        genero.disabled = false;
        especie.disabled = true;

        const response = await fetch(
            'index.php?controlador=RegistroTaxonomico&accion=obtenerGenerosPorSubfamilia&id_sub_familia=' + subfamilia.value
        );

        const datos = await response.json();

        datos.forEach(function(g) {
            agregarOpcion(genero, g.id, g.nombre);
        });

        actualizarResumen();
    }

    async function cargarEspeciesPorGenero() {
        limpiarSelect(especie, 'Seleccione una especie');
        agregarSP(especie);

        especie.disabled = false;

        const response = await fetch(
            'index.php?controlador=RegistroTaxonomico&accion=obtenerEspeciesPorGenero&id_genero=' + genero.value
        );

        const datos = await response.json();

        datos.forEach(function(e) {
            let texto = e.nombre_comun;

            if (texto == null || texto.trim() == '') {
                texto = e.nombre_cientifico;
            }

            if (texto != null && texto.trim() != '') {
                agregarOpcion(especie, e.id, texto);
            }
        });

        actualizarResumen();
    }

    orden.addEventListener('change', async function() {
        if (orden.value === '') {
            limpiarSelect(familia, 'Seleccione una familia');
            limpiarSelect(subfamilia, 'Seleccione una subfamilia');
            limpiarSelect(genero, 'Seleccione un género');
            limpiarSelect(especie, 'Seleccione una especie');

            familia.disabled = true;
            subfamilia.disabled = true;
            genero.disabled = true;
            especie.disabled = true;

            actualizarResumen();
            return;
        }

        if (orden.value === 'SP') {
            mostrarModal('Debe seleccionar al menos un orden real para realizar el registro.');
            ponerSPYBloquearDesde(familia);
            actualizarResumen();
            return;
        }

        await cargarFamiliasPorOrden();
    });

    familia.addEventListener('change', async function() {
        if (familia.value === '') {
            await cargarSubfamiliasPorOrden();
            return;
        }

        if (familia.value === 'SP') {
            await cargarSubfamiliasPorOrden();
            return;
        }

        await cargarSubfamiliasPorFamilia();
    });

    subfamilia.addEventListener('change', async function() {
        if (subfamilia.value === '') {
            if (familia.value !== '' && familia.value !== 'SP') {
                await cargarGenerosPorFamilia();
            } else {
                limpiarSelect(genero, 'Seleccione un género');
                agregarSP(genero);
                genero.disabled = true;

                limpiarSelect(especie, 'Seleccione una especie');
                agregarSP(especie);
                especie.disabled = true;

                actualizarResumen();
            }
            return;
        }

        if (subfamilia.value === 'SP') {
            if (familia.value !== '' && familia.value !== 'SP') {
                await cargarGenerosPorFamilia();
            } else {
                limpiarSelect(genero, 'Seleccione un género');
                agregarSP(genero);
                genero.value = 'SP';
                genero.disabled = true;

                limpiarSelect(especie, 'Seleccione una especie');
                agregarSP(especie);
                especie.value = 'SP';
                especie.disabled = true;

                actualizarResumen();
            }
            return;
        }

        await cargarGenerosPorSubfamilia();
    });

    genero.addEventListener('change', async function() {
        if (genero.value === '') {
            limpiarSelect(especie, 'Seleccione una especie');
            agregarSP(especie);
            especie.disabled = true;

            actualizarResumen();
            return;
        }

        if (genero.value === 'SP') {
            limpiarSelect(especie, 'Seleccione una especie');
            agregarSP(especie);
            especie.value = 'SP';
            especie.disabled = true;

            actualizarResumen();
            return;
        }
        await cargarEspeciesPorGenero();
    });
    especie.addEventListener('change', actualizarResumen);

    function guardarClasificacion() {
        if (orden.value === '' || orden.value === 'SP') {
            mostrarModal('Debe seleccionar al menos un orden real para guardar la clasificación.');
            return false;
        }

        mostrarModal('Clasificación taxonómica preparada correctamente.');
        return false;
    }

    actualizarResumen();
</script>

<?php include_once 'public/footer.php'; ?>