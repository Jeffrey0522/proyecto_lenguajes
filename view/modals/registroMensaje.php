<?php if ($mensaje != null) { ?>

<div id="modalMensaje" class="modal">

    <div class="modal-box">

        <h2>
            <?php
                if (strpos($mensaje, 'Error') !== false) {
                    echo '¡Error!';
                } else {
                    echo '¡Registro Exitoso!';
                }
            ?>
        </h2>

        <div class="linea"></div>

        <p><?php echo $mensaje; ?></p>

        <button onclick="cerrarModal()">
            Aceptar
        </button>

    </div>

</div>

<?php } ?>