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

function abrirModalActualizar(codigo) {
    document.getElementById('txtCodigoActualizar').innerText = codigo;
    document.getElementById('mod_codigo').value = codigo;
    document.getElementById('modalActualizar').style.display = 'flex';
}

function cerrarModalActualizar() {
    document.getElementById('modalActualizar').style.display = 'none';
    document.getElementById('formActualizarTaxonomia').reset();
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
    map.on('click', function (e) {
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('lat').value = e.latlng.lat;
        document.getElementById('lng').value = e.latlng.lng;
    });

    // 2. Selectores de Taxonomía
    const ordenSelect = document.getElementById('orden');
    const familiaSelect = document.getElementById('familia');
    const subfamiliaSelect = document.getElementById('subfamilia');
    const generoSelect = document.getElementById('genero');
    const especieSelect = document.getElementById('especie');

    function limpiarSelect(combo) {
        combo.innerHTML = '<option value="">Seleccione una opción</option>';
        combo.disabled = true;
    }

    function llenarSelect(combo, datos) {
        limpiarSelect(combo);
        datos.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;

            if (item.nombre_cientifico && item.nombre_comun) {
                option.textContent = `${item.nombre_comun} (${item.nombre_cientifico})`;
            } else if (item.nombre) {
                option.textContent = item.nombre;
            } else {
                option.textContent = "ID: " + item.id;
            }

            combo.appendChild(option);
        });
        combo.disabled = (datos.length === 0);
    }

    // 3. Eventos de cascada
    ordenSelect.addEventListener('change', function () {
        limpiarSelect(familiaSelect); limpiarSelect(subfamiliaSelect);
        limpiarSelect(generoSelect); limpiarSelect(especieSelect);
        if (this.value) fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerFamiliasPorOrden&id_orden=${this.value}`)
            .then(r => r.json()).then(d => llenarSelect(familiaSelect, d));
    });

    familiaSelect.addEventListener('change', function () {
        limpiarSelect(subfamiliaSelect); limpiarSelect(generoSelect); limpiarSelect(especieSelect);
        if (this.value) fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerSubfamiliasPorFamilia&id_familia=${this.value}`)
            .then(r => r.json()).then(d => llenarSelect(subfamiliaSelect, d));
    });

    subfamiliaSelect.addEventListener('change', function () {
        limpiarSelect(generoSelect); limpiarSelect(especieSelect);
        if (this.value) fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerGenerosPorSubfamilia&id_sub_familia=${this.value}`)
            .then(r => r.json()).then(d => llenarSelect(generoSelect, d));
    });

    generoSelect.addEventListener('change', function () {
        limpiarSelect(especieSelect);
        if (this.value) fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerEspeciesPorGenero&id_genero=${this.value}`)
            .then(r => r.json()).then(d => llenarSelect(especieSelect, d));
    });

    // 4. Formulario Principal
    document.getElementById('formRegistroEspecimen').addEventListener('submit', async function (e) {
        e.preventDefault();
        familiaSelect.disabled = false; subfamiliaSelect.disabled = false;
        generoSelect.disabled = false; especieSelect.disabled = false;

        const formData = new FormData(this);
        const response = await fetch('index.php?controlador=RegistroEspecimen&accion=registrar', { method: 'POST', body: formData });
        const data = await response.json();

        if (data.success) {
            mostrarModal(data.mensaje);
            setTimeout(() => window.location.reload(), 2000);
        } else {
            mostrarModal('Error: ' + data.error);
        }
    });

    // 5. Formulario Fotos
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