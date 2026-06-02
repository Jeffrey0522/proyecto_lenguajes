function mostrarModal(mensaje) {
    let modal = document.getElementById("modalMensaje");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "modalMensaje";
        modal.className = "modal-fondo";
        modal.innerHTML = `<div class="modal-contenido"><p id="modalTexto"></p><button type="button" onclick="cerrarModal()">Aceptar</button></div>`;
        document.body.appendChild(modal);
    }
    document.getElementById("modalTexto").innerText = mensaje;
    modal.style.display = "flex";
}

function cerrarModal() {
    const modal = document.getElementById("modalMensaje");
    if (modal) modal.style.display = "none";
}

// LÓGICA DEL MODAL DE ELIMINACIÓN

let codigoAEliminar = '';

function abrirModalConfirmacion(codigo) {
    codigoAEliminar = codigo;
    document.getElementById('txtCodigoEliminar').innerText = codigo;
    document.getElementById('modalConfirmacion').style.display = 'flex';
}

function cerrarModalConfirmacion() {
    codigoAEliminar = '';
    document.getElementById('modalConfirmacion').style.display = 'none';
}

async function ejecutarEliminacion() {
    if (!codigoAEliminar || codigoAEliminar === '') {
        alert("El sistema no detectó el código del espécimen.");
        return;
    }

    const formData = new FormData();
    formData.append('codigo', codigoAEliminar);

    cerrarModalConfirmacion();

    try {
        const response = await fetch('index.php?controlador=RegistroEspecimen&accion=eliminar', {
            method: 'POST',
            body: formData
        });

        const textoRespuesta = await response.text();

        try {
            const data = JSON.parse(textoRespuesta);
            if (data.success) {
                mostrarModal(data.mensaje);
                setTimeout(() => window.location.reload(), 1500);
            } else {
                mostrarModal('Error de BD: ' + data.error);
            }
        } catch (errorParseo) {
            console.error("Respuesta del servidor:", textoRespuesta);
            mostrarModal('Error del Servidor: Revisa la consola (F12) para ver el mensaje oculto de PHP.');
        }

    } catch (error) {
        console.error(error);
        mostrarModal('Error grave de conexión.');
    }
}

// LÓGICA DEL MODAL DE ACTUALIZACIÓN

function abrirModalActualizar(datos) {
    const codigo = typeof datos === 'object' ? datos.codigo : datos;
    document.getElementById('txtCodigoActualizar').innerText = codigo;
    document.getElementById('mod_codigo').value = codigo;
    document.getElementById('modalActualizar').style.display = 'flex';

    if (typeof datos === 'object') {
        document.dispatchEvent(new CustomEvent('precargarTaxonomiaActualizar', { detail: datos }));
    }
}

function cerrarModalActualizar() {
    document.getElementById('modalActualizar').style.display = 'none';
    document.getElementById('formActualizarTaxonomia').reset();
    ['mod_familia', 'mod_subfamilia', 'mod_genero', 'mod_especie'].forEach(function (id) {
        const combo = document.getElementById(id);
        if (combo) {
            combo.innerHTML = '<option value="">Seleccione una opcion</option>';
            combo.disabled = true;
        }
    });
}

// LÓGICA DEL CARRUSEL DE FOTOS

let carruselCodigo = '';
let carruselIndex = 0;
let carruselTotal = 0;
let preloadCache = null;

function abrirCarrusel(codigo) {
    carruselCodigo = codigo;
    carruselIndex = 0;
    preloadCache = null;
    document.getElementById('lblCodigoGaleria').innerText = codigo;
    document.getElementById('modalCarrusel').style.display = 'flex';

    cargarFotoVisual(carruselIndex);
}

function cerrarCarrusel() {
    document.getElementById('modalCarrusel').style.display = 'none';
    document.getElementById('imgCarrusel').style.display = 'none';
}

async function fetchFotoDB(codigo, offset) {
    const formData = new FormData();
    formData.append('codigo', codigo);
    formData.append('offset', offset);

    try {
        const response = await fetch('index.php?controlador=RegistroEspecimen&accion=obtenerImagenCarrusel', { method: 'POST', body: formData });
        return await response.json();
    } catch (e) {
        return { success: false };
    }
}

async function cargarFotoVisual(index) {
    const imgEl = document.getElementById('imgCarrusel');
    const loadEl = document.getElementById('loadingCarrusel');
    const btnPrev = document.getElementById('btnPrevFoto');
    const btnNext = document.getElementById('btnNextFoto');
    const lblCount = document.getElementById('lblContadorFotos');

    imgEl.style.display = 'none';
    loadEl.style.display = 'block';
    btnPrev.disabled = true;
    btnNext.disabled = true;

    let dataActual = null;

    if (preloadCache && preloadCache.offset === index) {
        dataActual = preloadCache.data;
    } else {
        dataActual = await fetchFotoDB(carruselCodigo, index);
    }

    if (dataActual && dataActual.success) {
        carruselTotal = dataActual.total;

        imgEl.onload = () => {
            loadEl.style.display = 'none';
            imgEl.style.display = 'block';
            lblCount.innerText = (index + 1) + " / " + carruselTotal;

            btnPrev.disabled = (index === 0);
            btnNext.disabled = (index >= carruselTotal - 1);
        };
        imgEl.src = dataActual.ruta;

        if (index < carruselTotal - 1) {
            fetchFotoDB(carruselCodigo, index + 1).then(nextData => {
                if (nextData.success) {
                    preloadCache = { offset: index + 1, data: nextData };
                    const imagenOculta = new Image();
                    imagenOculta.src = nextData.ruta;
                }
            });
        } else {
            preloadCache = null;
        }
    } else {
        cerrarCarrusel();
        mostrarModal("Este espécimen no tiene fotos asociadas.");
    }
}

function fotoAnterior() {
    if (carruselIndex > 0) {
        carruselIndex--;
        cargarFotoVisual(carruselIndex);
    }
}

function fotoSiguiente() {
    if (carruselIndex < carruselTotal - 1) {
        carruselIndex++;
        cargarFotoVisual(carruselIndex);
    }
}

// LÓGICA DEL MODAL AGREGAR FOTOS

function abrirModalAgregarFotos(codigo) {
    document.getElementById('txtCodigoAgregarFotos').innerText = codigo;
    document.getElementById('add_foto_codigo').value = codigo;
    document.getElementById('modalAgregarFotos').style.display = 'flex';
}

function cerrarModalAgregarFotos() {
    document.getElementById('modalAgregarFotos').style.display = 'none';
    document.getElementById('formAgregarFotos').reset();
}

// EVENTOS AL CARGAR LA PÁGINA (DOM CONTENT LOADED)

document.addEventListener('DOMContentLoaded', function () {
    // 1. Inicialización del Mapa
    const map = L.map('map').setView([9.90, -83.68], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker;
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    function colocarMarcador(lat, lng, centrarMapa) {
        const latLng = L.latLng(lat, lng);

        if (marker) {
            marker.setLatLng(latLng);
        } else {
            marker = L.marker(latLng).addTo(map);
        }

        if (centrarMapa) {
            map.setView(latLng, 13);
        }
    }

    function actualizarMapaDesdeInputs() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);

        if (Number.isNaN(lat) || Number.isNaN(lng)) {
            return;
        }

        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            return;
        }

        colocarMarcador(lat, lng, true);
    }

    map.on('click', function (e) {
        colocarMarcador(e.latlng.lat, e.latlng.lng, false);
        latInput.value = e.latlng.lat.toFixed(8);
        lngInput.value = e.latlng.lng.toFixed(8);
    });

    latInput.addEventListener('input', actualizarMapaDesdeInputs);
    lngInput.addEventListener('input', actualizarMapaDesdeInputs);

    // 2. Selectores de Taxonomía

    // 2. Selectores de Taxonomía

    const ordenSelect = document.getElementById('orden');
    const familiaSelect = document.getElementById('familia');
    const subfamiliaSelect = document.getElementById('subfamilia');
    const generoSelect = document.getElementById('genero');
    const especieSelect = document.getElementById('especie');

    function limpiarSelect(combo, texto) {
        combo.innerHTML = '<option value="">' + texto + '</option>';
    }

    function agregarSP(combo) {
        if (combo.querySelector('option[value="SP"]')) {
            return;
        }

        const option = document.createElement('option');
        option.value = 'SP';
        option.textContent = 'SP';
        combo.appendChild(option);
    }

    function prepararSelect(combo, texto) {
        limpiarSelect(combo, texto);
        agregarSP(combo);
    }

    function agregarOpcion(combo, value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        combo.appendChild(option);
    }

    function textoEspecie(item) {
        const nombreComun = item.nombre_comun || item.nombreComun || '';
        const nombreCientifico = item.nombre_cientifico || item.nombreCientifico || '';

        if (nombreCientifico.trim() !== '' || nombreComun.trim() !== '') {
            return [nombreCientifico, nombreComun].filter(Boolean).join(' - ');
        }

        return '';
    }

    function ponerSPYBloquearDesde(nivel) {
        if (nivel === 'familia') {
            prepararSelect(familiaSelect, 'Seleccione una familia');
            familiaSelect.value = 'SP';
            familiaSelect.disabled = true;
        }

        if (nivel === 'familia' || nivel === 'subfamilia') {
            prepararSelect(subfamiliaSelect, 'Seleccione una subfamilia');
            subfamiliaSelect.value = 'SP';
            subfamiliaSelect.disabled = true;
        }

        if (nivel === 'familia' || nivel === 'subfamilia' || nivel === 'genero') {
            prepararSelect(generoSelect, 'Seleccione un género');
            generoSelect.value = 'SP';
            generoSelect.disabled = true;
        }

        prepararSelect(especieSelect, 'Seleccione una especie');
        especieSelect.value = 'SP';
        especieSelect.disabled = true;
    }

    async function cargarFamiliasPorOrden() {
        prepararSelect(familiaSelect, 'Seleccione una familia');
        prepararSelect(subfamiliaSelect, 'Seleccione una subfamilia');
        prepararSelect(generoSelect, 'Seleccione un género');
        prepararSelect(especieSelect, 'Seleccione una especie');

        familiaSelect.disabled = false;
        subfamiliaSelect.disabled = false;
        generoSelect.disabled = true;
        especieSelect.disabled = true;

        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerFamiliasPorOrden&id_orden=' + ordenSelect.value
        );

        const datos = await response.json();

        datos.forEach(function (f) {
            agregarOpcion(familiaSelect, f.id, f.nombre);
        });

        await cargarSubfamiliasPorOrden();
    }

    async function cargarSubfamiliasPorOrden() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerSubfamiliasPorOrden&id_orden=' + ordenSelect.value
        );

        const datos = await response.json();

        prepararSelect(subfamiliaSelect, 'Seleccione una subfamilia');
        prepararSelect(generoSelect, 'Seleccione un género');
        prepararSelect(especieSelect, 'Seleccione una especie');

        subfamiliaSelect.disabled = false;
        generoSelect.disabled = true;
        especieSelect.disabled = true;

        datos.forEach(function (sf) {
            agregarOpcion(subfamiliaSelect, sf.id, sf.nombre);
        });
    }

    async function cargarSubfamiliasPorFamilia() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerSubfamiliasPorFamilia&id_familia=' + familiaSelect.value
        );

        const datos = await response.json();

        prepararSelect(subfamiliaSelect, 'Seleccione una subfamilia');
        prepararSelect(generoSelect, 'Seleccione un género');
        prepararSelect(especieSelect, 'Seleccione una especie');

        subfamiliaSelect.disabled = false;
        generoSelect.disabled = true;
        especieSelect.disabled = true;

        datos.forEach(function (sf) {
            agregarOpcion(subfamiliaSelect, sf.id, sf.nombre);
        });
    }

    async function cargarGenerosPorFamilia() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerGenerosPorFamilia&id_familia=' + familiaSelect.value
        );

        const datos = await response.json();

        prepararSelect(generoSelect, 'Seleccione un género');
        prepararSelect(especieSelect, 'Seleccione una especie');

        generoSelect.disabled = false;
        especieSelect.disabled = true;

        datos.forEach(function (g) {
            agregarOpcion(generoSelect, g.id, g.nombre);
        });
    }

    async function cargarGenerosPorSubfamilia() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerGenerosPorSubfamilia&id_sub_familia=' + subfamiliaSelect.value
        );

        const datos = await response.json();

        prepararSelect(generoSelect, 'Seleccione un género');
        prepararSelect(especieSelect, 'Seleccione una especie');

        generoSelect.disabled = false;
        especieSelect.disabled = true;

        datos.forEach(function (g) {
            agregarOpcion(generoSelect, g.id, g.nombre);
        });
    }

    async function cargarEspeciesPorGenero() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerEspeciesPorGenero&id_genero=' + generoSelect.value
        );

        const datos = await response.json();

        prepararSelect(especieSelect, 'Seleccione una especie');
        especieSelect.disabled = false;

        datos.forEach(function (e) {
            const texto = textoEspecie(e);

            if (texto && texto.trim() !== '') {
                agregarOpcion(especieSelect, e.id, texto);
            }
        });
    }

    ordenSelect.addEventListener('change', async function () {
        if (ordenSelect.value === '') {
            limpiarSelect(familiaSelect, 'Seleccione una familia');
            limpiarSelect(subfamiliaSelect, 'Seleccione una subfamilia');
            limpiarSelect(generoSelect, 'Seleccione un género');
            limpiarSelect(especieSelect, 'Seleccione una especie');

            familiaSelect.disabled = true;
            subfamiliaSelect.disabled = true;
            generoSelect.disabled = true;
            especieSelect.disabled = true;
            return;
        }

        if (ordenSelect.value === 'SP') {
            mostrarModal('Debe seleccionar al menos un orden real para registrar el espécimen.');
            ponerSPYBloquearDesde('familia');
            return;
        }

        await cargarFamiliasPorOrden();
    });

    familiaSelect.addEventListener('change', async function () {
        prepararSelect(subfamiliaSelect, 'Seleccione una subfamilia');
        prepararSelect(generoSelect, 'Seleccione un género');
        prepararSelect(especieSelect, 'Seleccione una especie');

        subfamiliaSelect.disabled = false;
        generoSelect.disabled = true;
        especieSelect.disabled = true;

        if (familiaSelect.value === '') {
            await cargarSubfamiliasPorOrden();
            return;
        }

        if (familiaSelect.value === 'SP') {
            await cargarSubfamiliasPorOrden();
            return;
        }

        await cargarSubfamiliasPorFamilia();
        await cargarGenerosPorFamilia();
    });

    subfamiliaSelect.addEventListener('change', async function () {
        if (subfamiliaSelect.value === '') {
            if (familiaSelect.value !== '' && familiaSelect.value !== 'SP') {
                await cargarGenerosPorFamilia();
            } else {
                prepararSelect(generoSelect, 'Seleccione un género');
                prepararSelect(especieSelect, 'Seleccione una especie');

                generoSelect.disabled = true;
                especieSelect.disabled = true;
            }

            return;
        }

        if (subfamiliaSelect.value === 'SP') {
            if (familiaSelect.value !== '' && familiaSelect.value !== 'SP') {
                await cargarGenerosPorFamilia();
            } else {
                prepararSelect(generoSelect, 'Seleccione un género');
                generoSelect.value = 'SP';
                generoSelect.disabled = true;

                prepararSelect(especieSelect, 'Seleccione una especie');
                especieSelect.value = 'SP';
                especieSelect.disabled = true;
            }

            return;
        }

        await cargarGenerosPorSubfamilia();
    });

    generoSelect.addEventListener('change', async function () {
        if (generoSelect.value === '') {
            prepararSelect(especieSelect, 'Seleccione una especie');
            especieSelect.disabled = true;
            return;
        }

        if (generoSelect.value === 'SP') {
            prepararSelect(especieSelect, 'Seleccione una especie');
            especieSelect.value = 'SP';
            especieSelect.disabled = true;
            return;
        }

        await cargarEspeciesPorGenero();
    });

    // 4. Selectores del modal de actualizacion
    const modOrdenSelect = document.getElementById('mod_orden');
    const modFamiliaSelect = document.getElementById('mod_familia');
    const modSubfamiliaSelect = document.getElementById('mod_subfamilia');
    const modGeneroSelect = document.getElementById('mod_genero');
    const modEspecieSelect = document.getElementById('mod_especie');

    function limpiarSelectModal(combo, texto) {
        combo.innerHTML = '<option value="">' + texto + '</option>';
        combo.disabled = true;
    }

    function agregarSPModal(combo) {
        if (!combo.querySelector('option[value="SP"]')) {
            const option = document.createElement('option');
            option.value = 'SP';
            option.textContent = 'SP';
            combo.appendChild(option);
        }
    }

    function prepararSelectModal(combo, texto) {
        limpiarSelectModal(combo, texto);
        agregarSPModal(combo);
    }

    function llenarSelectModal(combo, datos, texto, tipo) {
        prepararSelectModal(combo, texto);

        datos.forEach(function (item) {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = tipo === 'especie' ? textoEspecie(item) : item.nombre;

            if (option.textContent && option.textContent.trim() !== '') {
                combo.appendChild(option);
            }
        });

        combo.disabled = false;
    }

    function normalizarValorModal(valor, esOrden) {
        if (valor === undefined || valor === null || valor === '') {
            return esOrden ? '' : 'SP';
        }

        return String(valor);
    }

    async function cargarFamiliasModal() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerFamiliasPorOrden&id_orden=' + modOrdenSelect.value
        );
        const datos = await response.json();
        llenarSelectModal(modFamiliaSelect, datos, 'Seleccione una familia');
        await cargarSubfamiliasPorOrdenModal();
    }

    async function cargarSubfamiliasPorOrdenModal() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerSubfamiliasPorOrden&id_orden=' + modOrdenSelect.value
        );
        const datos = await response.json();
        llenarSelectModal(modSubfamiliaSelect, datos, 'Seleccione una subfamilia');
    }

    async function cargarSubfamiliasPorFamiliaModal() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerSubfamiliasPorFamilia&id_familia=' + modFamiliaSelect.value
        );
        const datos = await response.json();
        llenarSelectModal(modSubfamiliaSelect, datos, 'Seleccione una subfamilia');
    }

    async function cargarGenerosPorFamiliaModal() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerGenerosPorFamilia&id_familia=' + modFamiliaSelect.value
        );
        const datos = await response.json();
        llenarSelectModal(modGeneroSelect, datos, 'Seleccione un genero');
    }

    async function cargarGenerosPorSubfamiliaModal() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerGenerosPorSubfamilia&id_sub_familia=' + modSubfamiliaSelect.value
        );
        const datos = await response.json();
        llenarSelectModal(modGeneroSelect, datos, 'Seleccione un genero');
    }

    async function cargarEspeciesPorGeneroModal() {
        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=obtenerEspeciesPorGenero&id_genero=' + modGeneroSelect.value
        );
        const datos = await response.json();
        llenarSelectModal(modEspecieSelect, datos, 'Seleccione una especie', 'especie');
    }

    async function precargarModalActualizar(datos) {
        limpiarSelectModal(modFamiliaSelect, 'Seleccione una familia');
        limpiarSelectModal(modSubfamiliaSelect, 'Seleccione una subfamilia');
        limpiarSelectModal(modGeneroSelect, 'Seleccione un genero');
        limpiarSelectModal(modEspecieSelect, 'Seleccione una especie');

        modOrdenSelect.value = normalizarValorModal(datos.id_orden, true);

        if (modOrdenSelect.value === '' || modOrdenSelect.value === 'SP') {
            return;
        }

        await cargarFamiliasModal();
        modFamiliaSelect.value = normalizarValorModal(datos.id_familia, false);

        if (modFamiliaSelect.value !== 'SP') {
            await cargarSubfamiliasPorFamiliaModal();
            await cargarGenerosPorFamiliaModal();
        }

        modSubfamiliaSelect.value = normalizarValorModal(datos.id_subfamilia, false);

        if (modSubfamiliaSelect.value !== 'SP') {
            await cargarGenerosPorSubfamiliaModal();
        }

        modGeneroSelect.value = normalizarValorModal(datos.id_genero, false);

        if (modGeneroSelect.value !== 'SP') {
            await cargarEspeciesPorGeneroModal();
        } else {
            prepararSelectModal(modEspecieSelect, 'Seleccione una especie');
            modEspecieSelect.value = 'SP';
            modEspecieSelect.disabled = false;
        }

        modEspecieSelect.value = normalizarValorModal(datos.id_especie, false);
    }

    if (modOrdenSelect && modFamiliaSelect && modSubfamiliaSelect && modGeneroSelect && modEspecieSelect) {
        modOrdenSelect.addEventListener('change', async function () {
            limpiarSelectModal(modFamiliaSelect, 'Seleccione una familia');
            limpiarSelectModal(modSubfamiliaSelect, 'Seleccione una subfamilia');
            limpiarSelectModal(modGeneroSelect, 'Seleccione un genero');
            limpiarSelectModal(modEspecieSelect, 'Seleccione una especie');

            if (modOrdenSelect.value === '' || modOrdenSelect.value === 'SP') {
                if (modOrdenSelect.value === 'SP') {
                    mostrarModal('Debe seleccionar un orden real para actualizar el especimen.');
                }
                return;
            }

            await cargarFamiliasModal();
        });

        modFamiliaSelect.addEventListener('change', async function () {
            limpiarSelectModal(modSubfamiliaSelect, 'Seleccione una subfamilia');
            limpiarSelectModal(modGeneroSelect, 'Seleccione un genero');
            limpiarSelectModal(modEspecieSelect, 'Seleccione una especie');

            if (modFamiliaSelect.value === '') {
                await cargarSubfamiliasPorOrdenModal();
                return;
            }

            if (modFamiliaSelect.value === 'SP') {
                await cargarSubfamiliasPorOrdenModal();
                return;
            }

            await cargarSubfamiliasPorFamiliaModal();
            await cargarGenerosPorFamiliaModal();
        });

        modSubfamiliaSelect.addEventListener('change', async function () {
            limpiarSelectModal(modGeneroSelect, 'Seleccione un genero');
            limpiarSelectModal(modEspecieSelect, 'Seleccione una especie');

            if (modSubfamiliaSelect.value === '') {
                if (modFamiliaSelect.value !== '' && modFamiliaSelect.value !== 'SP') {
                    await cargarGenerosPorFamiliaModal();
                }
                return;
            }

            if (modSubfamiliaSelect.value === 'SP') {
                if (modFamiliaSelect.value !== '' && modFamiliaSelect.value !== 'SP') {
                    await cargarGenerosPorFamiliaModal();
                } else {
                    prepararSelectModal(modGeneroSelect, 'Seleccione un genero');
                    modGeneroSelect.value = 'SP';
                    modGeneroSelect.disabled = false;

                    prepararSelectModal(modEspecieSelect, 'Seleccione una especie');
                    modEspecieSelect.value = 'SP';
                    modEspecieSelect.disabled = false;
                }
                return;
            }

            await cargarGenerosPorSubfamiliaModal();
        });

        modGeneroSelect.addEventListener('change', async function () {
            limpiarSelectModal(modEspecieSelect, 'Seleccione una especie');

            if (modGeneroSelect.value === '') return;

            if (modGeneroSelect.value === 'SP') {
                prepararSelectModal(modEspecieSelect, 'Seleccione una especie');
                modEspecieSelect.value = 'SP';
                modEspecieSelect.disabled = false;
                return;
            }

            await cargarEspeciesPorGeneroModal();
        });

        document.addEventListener('precargarTaxonomiaActualizar', function (event) {
            precargarModalActualizar(event.detail);
        });
    }

    // 5. Formulario Principal

    document.getElementById('formRegistroEspecimen').addEventListener('submit', async function (e) {
        e.preventDefault();

        familiaSelect.disabled = false;
        subfamiliaSelect.disabled = false;
        generoSelect.disabled = false;
        especieSelect.disabled = false;

        const formData = new FormData(this);

        const response = await fetch(
            'index.php?controlador=RegistroEspecimen&accion=registrar',
            {
                method: 'POST',
                body: formData
            }
        );

        const data = await response.json();

        if (data.success) {
            mostrarModal(data.mensaje);
            setTimeout(() => window.location.reload(), 2000);
        } else {
            mostrarModal(data.error);
        }
    });

    // 6. Formulario de actualizacion de taxonomia
    const formActualizarTaxonomia = document.getElementById('formActualizarTaxonomia');
    if (formActualizarTaxonomia) {
        formActualizarTaxonomia.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (modFamiliaSelect) modFamiliaSelect.disabled = false;
            if (modSubfamiliaSelect) modSubfamiliaSelect.disabled = false;
            if (modGeneroSelect) modGeneroSelect.disabled = false;
            if (modEspecieSelect) modEspecieSelect.disabled = false;

            const formData = new FormData(this);
            const response = await fetch('index.php?controlador=RegistroEspecimen&accion=actualizarTaxonomia', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                cerrarModalActualizar();
                mostrarModal('Taxonomia actualizada correctamente.');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                mostrarModal('Error: ' + data.error);
            }
        });
    }

    // 7. Formulario Fotos
    const formAgregarFotos = document.getElementById('formAgregarFotos');
    if (formAgregarFotos) {
        formAgregarFotos.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch('index.php?controlador=RegistroEspecimen&accion=agregarImagenes', { method: 'POST', body: formData });
            const data = await response.json();
            if (data.success) {
                cerrarModalAgregarFotos();
                mostrarModal(data.mensaje);
            } else {
                mostrarModal('Error: ' + data.error);
            }
        });
    }

    // 6. Lógica de Gavetas y Viales
    const radioGaveta = document.querySelector('input[value="gaveta"]');
    const radioVial = document.querySelector('input[value="vial"]');
    const divGaveta = document.getElementById('gavetaSelect');
    const divVial = document.getElementById('vialSelect');

    // --- LÓGICA DE FILTRADO DE GAVETAS ---
    const gabineteSelect = document.getElementById('gabinete');
    const gavetaSelect = document.getElementById('gaveta');

    // Guardamos solo las opciones reales (excluyendo la opción por defecto)
    const todasLasGavetas = Array.from(gavetaSelect.options).filter(op => op.value !== "");

    gabineteSelect.addEventListener('change', function () {
        const gabineteSeleccionado = this.value;

        // Limpiamos el combo de gavetas y dejamos la opción base
        gavetaSelect.innerHTML = '<option value="">Seleccione una gaveta</option>';

        if (gabineteSeleccionado !== "") {
            // Filtramos las opciones que coinciden con el data-gabinete
            todasLasGavetas.forEach(opcion => {
                if (opcion.getAttribute('data-gabinete') === gabineteSeleccionado) {
                    gavetaSelect.appendChild(opcion.cloneNode(true));
                }
            });
        }
    });

    function actualizarVisibilidad() {
        divGaveta.style.display = radioGaveta.checked ? 'block' : 'none';
        divVial.style.display = radioVial.checked ? 'block' : 'none';
    }

    if (radioGaveta) radioGaveta.addEventListener('change', actualizarVisibilidad);
    if (radioVial) radioVial.addEventListener('change', actualizarVisibilidad);

    // --- LÓGICA DE FILTRADO DE VIALES ---
    const cajaSelect = document.getElementById('caja');
    const vialSelect = document.getElementById('vial');

    // Guardamos todas las opciones de viales (excluyendo la opción por defecto)
    const todosLosViales = Array.from(vialSelect.options).filter(op => op.value !== "");

    cajaSelect.addEventListener('change', function () {
        const cajaSeleccionada = this.value;

        // Limpiamos el combo de viales y dejamos la opción base
        vialSelect.innerHTML = '<option value="">Seleccione un vial</option>';

        if (cajaSeleccionada !== "") {
            // Filtramos solo los viales que pertenecen a la caja seleccionada
            todosLosViales.forEach(opcion => {
                if (opcion.getAttribute('data-caja') === cajaSeleccionada) {
                    vialSelect.appendChild(opcion.cloneNode(true));
                }
            });
        } else {
            // Si no se selecciona caja, mensaje inicial
            vialSelect.innerHTML = '<option value="">Seleccione primero una caja</option>';
        }
    });
});
