function mostrarModal(mensaje) {
    let modal = document.getElementById("modalMensaje");

    if (!modal) {
        modal = document.createElement("div");
        modal.id = "modalMensaje";
        modal.className = "modal";

        modal.innerHTML = `
    <div class="modal-box">
        <p id="modalTexto"></p>
        <button type="button" onclick="cerrarModal()">Aceptar</button>
    </div>
`;

        document.body.appendChild(modal);
    }

    document.getElementById("modalTexto").innerText = mensaje;
    modal.style.display = "flex";
}

function cerrarModal() {
    const modal = document.getElementById("modalMensaje");

    if (modal) {
        modal.style.display = "none";
    }
}

function avisarCambio() {
    mostrarModal("Recuerde presionar el botón Actualizar para guardar los cambios.");
}

function validarEspecie() {
    const nombreCientifico = document.querySelector('input[name="nombre_cientifico"]').value.trim();
    const nombreComun = document.querySelector('input[name="nombre_comun"]').value.trim();

    if (nombreCientifico === "" && nombreComun === "") {
        mostrarModal("Debe ingresar al menos el nombre común o el nombre científico.");
        return false;
    }

    return true;
}

window.addEventListener('load', function () {

    const mensaje = localStorage.getItem('mensajeSistema');

    if (mensaje) {

        mostrarModal(mensaje);

        localStorage.removeItem('mensajeSistema');
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const ordenSelect = document.getElementById('orden');

    if (!ordenSelect) return;

    const familiaSelect = document.getElementById('familia');
    const subfamiliaSelect = document.getElementById('subfamilia');
    const generoSelect = document.getElementById('genero');
    const especieSelect = document.getElementById('especie');

    const familiasPorOrden = JSON.parse(ordenSelect.getAttribute('data-familias'));
    const subfamiliasPorFamilia = JSON.parse(ordenSelect.getAttribute('data-subfamilias'));
    const generosPorSubfamilia = JSON.parse(ordenSelect.getAttribute('data-generos'));
    const especiesPorGenero = JSON.parse(ordenSelect.getAttribute('data-especies'));

    function actualizarCombo(combo, opciones) {
        combo.innerHTML = '<option value="">Seleccione una opción</option>';
        opciones.forEach(op => {
            const option = document.createElement('option');
            option.value = op.id;
            option.textContent = op.nombre;
            combo.appendChild(option);
        });
        combo.disabled = (combo.options.length <= 1);
    }

    ordenSelect.addEventListener('change', function () {
        const ordenId = this.value;
        const familias = familiasPorOrden[ordenId] || [];
        actualizarCombo(familiaSelect, familias);

        actualizarCombo(subfamiliaSelect, []);
        actualizarCombo(generoSelect, []);
        actualizarCombo(especieSelect, []);
    });

    familiaSelect.addEventListener('change', function () {
        const familiaId = this.value;
        const subfamilias = subfamiliasPorFamilia[familiaId] || [];
        actualizarCombo(subfamiliaSelect, subfamilias);

        actualizarCombo(generoSelect, []);
        actualizarCombo(especieSelect, []);
    });

    subfamiliaSelect.addEventListener('change', function () {
        const subfamId = this.value;
        const generos = generosPorSubfamilia[subfamId] || [];
        actualizarCombo(generoSelect, generos);

        actualizarCombo(especieSelect, []);
    });

    generoSelect.addEventListener('change', function () {
        const generoId = this.value;
        const especies = especiesPorGenero[generoId] || [];
        actualizarCombo(especieSelect, especies);
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const ordenSelect = document.getElementById('orden');

    if (!ordenSelect) return;

    const familiaSelect = document.getElementById('familia');
    const subfamiliaSelect = document.getElementById('subfamilia');
    const generoSelect = document.getElementById('genero');
    const especieSelect = document.getElementById('especie');

    function limpiarSelect(combo) {
        combo.innerHTML = '<option value="">Seleccione una opción</option>';
    }

    function llenarSelect(combo, datos, textoInicial) {
        combo.innerHTML = '<option value="">' + textoInicial + '</option>';

        if (!combo.querySelector('option[value="SP"]')) {
            const opcionSP = document.createElement('option');
            opcionSP.value = 'SP';
            opcionSP.textContent = 'SP';
            combo.appendChild(opcionSP);
        }

        datos.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;

            const nombreCientifico = item.nombre_cientifico || item.nombreCientifico || '';
            const nombreComun = item.nombre_comun || item.nombreComun || '';

            if (nombreComun.trim() !== '') {
                option.textContent = nombreComun;
            } else if (nombreCientifico.trim() !== '') {
                option.textContent = nombreCientifico;
            } else if (item.nombre) {
                option.textContent = item.nombre;
            } else {
                option.textContent = "ID: " + item.id;
            }

            combo.appendChild(option);
        });

        combo.disabled = false;
    }

    ordenSelect.addEventListener('change', function () {
        const id = this.value;
        limpiarSelect(familiaSelect);
        limpiarSelect(subfamiliaSelect);
        limpiarSelect(generoSelect);
        limpiarSelect(especieSelect);

        fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerFamiliasPorOrden&id_orden=${id}`)
            .then(res => res.json())
            .then(data => llenarSelect(familiaSelect, data, 'Seleccione una familia'));
    });

    familiaSelect.addEventListener('change', function () {
        const id = this.value;
        limpiarSelect(subfamiliaSelect);
        limpiarSelect(generoSelect);
        limpiarSelect(especieSelect);

        fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerSubfamiliasPorFamilia&id_familia=${id}`)
            .then(res => res.json())
            .then(data => llenarSelect(subfamiliaSelect, data, 'Seleccione una subfamilia'));
    });

    subfamiliaSelect.addEventListener('change', function () {
        const id = this.value;
        limpiarSelect(generoSelect);
        limpiarSelect(especieSelect);

        fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerGenerosPorSubfamilia&id_sub_familia=${id}`)
            .then(res => res.json())
            .then(data => llenarSelect(generoSelect, data, 'Seleccione un género'));
    });

    generoSelect.addEventListener('change', function () {
        const id = this.value;
        limpiarSelect(especieSelect);

        fetch(`index.php?controlador=RegistroEspecimen&accion=obtenerEspeciesPorGenero&id_genero=${id}`)
            .then(res => res.json())
            .then(data => llenarSelect(especieSelect, data, 'Seleccione una especie'));
    });
});
