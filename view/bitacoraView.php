<?php include_once 'public/headerSuperAdmin.php'; ?>

<?php
$bitacoras = isset($bitacoras) ? $bitacoras : array();
$desde = isset($desde) ? $desde : "";
$hasta = isset($hasta) ? $hasta : "";
$usuario = isset($usuario) ? $usuario : "";

// Obtener fecha y hora actual en formato HTML5
$fechaActual = date('Y-m-d\TH:i');
?>

<style>
    .bitacora-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .bitacora-header h2 {
        margin: 0;
    }
    
    .export-buttons {
        display: flex;
        gap: 10px;
    }
    
    .export-buttons button {
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }
    
    .export-buttons button:hover {
        background-color: #0056b3;
    }
    
    .export-buttons .pdf {
        background-color: #dc3545;
    }
    
    .export-buttons .pdf:hover {
        background-color: #c82333;
    }
    
    .export-buttons .excel {
        background-color: #28a745;
    }
    
    .export-buttons .excel:hover {
        background-color: #218838;
    }
</style>

<div class="bitacora-header">
    <h2>Bitácora de Actividad</h2>
    <div class="export-buttons">
        <button type="button" class="pdf" onclick="exportarPDF()">Exportar PDF</button>
        <button type="button" class="excel" onclick="exportarExcel()">Exportar Excel</button>
    </div>
</div>

<!-- FILTROS -->
<form method="POST" action="?controlador=bitacora&accion=mostrar">

    <label>Desde:</label>
    <input type="datetime-local" name="desde" value="<?php echo $desde; ?>" max="<?php echo $fechaActual; ?>">

    <label>Hasta:</label>
    <input type="datetime-local" name="hasta" value="<?php echo $hasta; ?>" max="<?php echo $fechaActual; ?>">

    <label>Usuario:</label>
    <input type="text" name="usuario" value="<?php echo $usuario; ?>" placeholder="Cédula usuario">

    <button type="submit">Filtrar</button>
</form>

<br>

<!-- TABLA -->
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Acción</th>
            <th>Tabla</th>
            <th>ID Registro</th>
            <th>Cédula Registro</th>
            <th>Fecha</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($bitacoras as $b) { ?>
            <tr>
                <td><?php echo $b['id']; ?></td>
                <td><?php echo $b['cedula_usuario']; ?></td>
                <td><?php echo $b['accion']; ?></td>
                <td><?php echo $b['tabla_afectada']; ?></td>
                <td><?php echo $b['id_registro']; ?></td>
                <td><?php echo $b['cedula_registro']; ?></td>
                <td><?php echo $b['fecha_creacion']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    // Función para exportar a PDF
    function exportarPDF() {
        const desde = document.querySelector('input[name="desde"]').value;
        const hasta = document.querySelector('input[name="hasta"]').value;
        const usuario = document.querySelector('input[name="usuario"]').value;
        
        // Construir parámetros de consulta
        let params = new URLSearchParams();
        if (desde) params.append('desde', desde);
        if (hasta) params.append('hasta', hasta);
        if (usuario) params.append('usuario', usuario);
        params.append('formato', 'pdf');
        
        // Redirigir a la descarga
        window.location.href = '?controlador=bitacora&accion=exportar&' + params.toString();
    }
    
    // Función para exportar a Excel
    function exportarExcel() {
        const desde = document.querySelector('input[name="desde"]').value;
        const hasta = document.querySelector('input[name="hasta"]').value;
        const usuario = document.querySelector('input[name="usuario"]').value;
        
        // Construir parámetros de consulta
        let params = new URLSearchParams();
        if (desde) params.append('desde', desde);
        if (hasta) params.append('hasta', hasta);
        if (usuario) params.append('usuario', usuario);
        params.append('formato', 'excel');
        
        // Redirigir a la descarga
        window.location.href = '?controlador=bitacora&accion=exportar&' + params.toString();
    }
</script>

<?php include_once 'public/footer.php'; ?>