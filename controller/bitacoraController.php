<?php

require_once 'model/bitacoraModel.php';

class bitacoraController
{
    public function __construct()
    {
        $this->view = new View();
    }

    public function mostrar()
    {
        $bitacora = new bitacoraModel();

        $desde = isset($_POST['desde']) ? $_POST['desde'] : null;
        $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : null;
        $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : "";

        $data['bitacoras'] = $bitacora->listar($desde, $hasta, $usuario);

        $data['desde'] = $desde;
        $data['hasta'] = $hasta;
        $data['usuario'] = $usuario;

        $this->view->show("bitacoraView.php", $data);
    }

    public function exportar()
    {
        $bitacora = new bitacoraModel();

        $desde = isset($_GET['desde']) ? $_GET['desde'] : null;
        $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;
        $usuario = isset($_GET['usuario']) ? $_GET['usuario'] : "";
        $formato = isset($_GET['formato']) ? $_GET['formato'] : 'excel';

        $bitacoras = $bitacora->listar($desde, $hasta, $usuario);

        if ($formato == 'excel') {
            $this->exportarExcel($bitacoras, $desde, $hasta, $usuario);
        } elseif ($formato == 'pdf') {
            $this->exportarPDF($bitacoras, $desde, $hasta, $usuario);
        }
    }

    private function exportarExcel($bitacoras, $desde, $hasta, $usuario)
    {
        // Encabezados para descargar como Excel (CSV)
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="bitacora_' . date('Y-m-d_H-i-s') . '.csv"');
        
        // Crear archivo CSV
        $output = fopen('php://output', 'w');
        
        // Configurar BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados de columna
        fputcsv($output, array('ID', 'Usuario', 'Acción', 'Tabla', 'ID Registro', 'Cédula Registro', 'Fecha'), ';');
        
        // Datos de bitácora
        foreach ($bitacoras as $b) {
            fputcsv($output, array(
                $b['id'],
                $b['cedula_usuario'],
                $b['accion'],
                $b['tabla_afectada'],
                $b['id_registro'],
                $b['cedula_registro'],
                $b['fecha_creacion']
            ), ';');
        }
        
        fclose($output);
        exit;
    }

    private function exportarPDF($bitacoras, $desde, $hasta, $usuario)
    {
        // Generar HTML para PDF
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Bitácora de Actividad</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h2 { text-align: center; }
                .filtros { margin-bottom: 20px; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 11px; }
                th { background-color: #f0f0f0; font-weight: bold; }
                tr:nth-child(even) { background-color: #f9f9f9; }
            </style>
        </head>
        <body>
            <h2>Bitácora de Actividad</h2>
            <div class="filtros">
                <p><strong>Filtros aplicados:</strong></p>
                <ul>
                    ' . ($desde ? '<li>Desde: ' . htmlspecialchars($desde) . '</li>' : '') . '
                    ' . ($hasta ? '<li>Hasta: ' . htmlspecialchars($hasta) . '</li>' : '') . '
                    ' . ($usuario ? '<li>Usuario: ' . htmlspecialchars($usuario) . '</li>' : '') . '
                    <li>Fecha de generación: ' . date('Y-m-d H:i:s') . '</li>
                </ul>
            </div>
            <table>
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
                <tbody>';

        foreach ($bitacoras as $b) {
            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($b['id']) . '</td>
                        <td>' . htmlspecialchars($b['cedula_usuario']) . '</td>
                        <td>' . htmlspecialchars($b['accion']) . '</td>
                        <td>' . htmlspecialchars($b['tabla_afectada']) . '</td>
                        <td>' . htmlspecialchars($b['id_registro']) . '</td>
                        <td>' . htmlspecialchars($b['cedula_registro']) . '</td>
                        <td>' . htmlspecialchars($b['fecha_creacion']) . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        // Enviar como descarga HTML (se puede abrir en navegador e imprimir como PDF)
        header('Content-Type: application/octet-stream; charset=utf-8');
        header('Content-Disposition: attachment; filename="bitacora_' . date('Y-m-d_H-i-s') . '.html"');
        echo $html;
        exit;
    }
}