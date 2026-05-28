function mostrarModal(mensaje) {
    let modal = document.getElementById("modalMensaje");

    if (!modal) {
        modal = document.createElement("div");
        modal.id = "modalMensaje";
        modal.className = "modal-fondo";

        modal.innerHTML = `
    <div class="modal-contenido">
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