document.addEventListener('DOMContentLoaded', function() {
    const radioGaveta = document.querySelector('input[value="gaveta"]');
    const radioVial = document.querySelector('input[value="vial"]');
    const gavetaDiv = document.getElementById('gavetaSelect');
    const vialDiv = document.getElementById('vialSelect');

    function actualizarVisibilidad() {
        if (radioGaveta.checked) {
            gavetaDiv.style.display = 'block';
            vialDiv.style.display = 'none';
        } else {
            gavetaDiv.style.display = 'none';
            vialDiv.style.display = 'block';
        }
    }

    radioGaveta.addEventListener('change', actualizarVisibilidad);
    radioVial.addEventListener('change', actualizarVisibilidad);

    actualizarVisibilidad(); // Inicial al cargar la página
});