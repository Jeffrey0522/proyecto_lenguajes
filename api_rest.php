<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require 'libs/configuration.php';
require_once 'libs/SPDO.php';

$db = SPDO::singleton();
$db->exec("SET NAMES utf8");
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {

    // =====================================================================
    //  HU-19 (Dev 4): REGISTRO Y LOGIN USUARIO EXTERNO
    // =====================================================================

    case 'registrar_planta':

        $datos = json_decode(file_get_contents("php://input"), true);

        $stmt = $db->prepare(
            "CALL sp_registrar_planta(?, ?, ?)"
        );

        $stmt->bindParam(1, $datos['nombre_comun']);
        $stmt->bindParam(2, $datos['nombre_cientifico']);
        $stmt->bindParam(3, $datos['descripcion']);

        try {

            $stmt->execute();
            $stmt->closeCursor();

            echo json_encode([
                "mensaje" => "Planta registrada"
            ]);
        } catch (PDOException $e) {

            http_response_code(400);

            echo json_encode([
                "mensaje" => $e->getMessage()
            ]);
        }

        break;

    case 'listar_plantas':

        $busqueda = isset($_GET['busqueda'])
            ? $_GET['busqueda']
            : '';

        $stmt = $db->prepare(
            "CALL sp_listar_plantas(?)"
        );

        $stmt->bindParam(1, $busqueda);

        $stmt->execute();

        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        echo json_encode($datos);

        break;

    case 'obtener_planta':

        $id = $_GET['id'];

        $stmt = $db->prepare(
            "CALL sp_obtener_planta(?)"
        );

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        echo json_encode($datos);

        break;

    case 'actualizar_planta':

        $datos = json_decode(
            file_get_contents("php://input"),
            true
        );

        $stmt = $db->prepare(
            "CALL sp_actualizar_planta(?, ?, ?, ?)"
        );

        $stmt->bindParam(1, $datos['id']);
        $stmt->bindParam(2, $datos['nombre_comun']);
        $stmt->bindParam(3, $datos['nombre_cientifico']);
        $stmt->bindParam(4, $datos['descripcion']);

        $stmt->execute();

        $stmt->closeCursor();

        echo json_encode([
            "mensaje" => "Actualizada"
        ]);

        break;

    case 'eliminar_planta':

        $id = $_GET['id'];

        $stmt = $db->prepare(
            "CALL sp_eliminar_planta(?)"
        );

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $stmt->closeCursor();

        echo json_encode([
            "mensaje" => "Eliminada"
        ]);

        break;

    case 'registrar_usuario':
        $datos = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("CALL sp_registrar_usuario_externo(?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $datos['cedula']);
        $stmt->bindParam(2, $datos['nombre']);
        $stmt->bindParam(3, $datos['apellido']);
        $stmt->bindParam(4, $datos['correo']);
        $hash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
        $stmt->bindParam(5, $hash);
        $stmt->bindParam(6, $datos['nombre_usuario']);
        try {
            $stmt->execute();
            
            // Verificar si hay errores en la ejecución del SP
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != '00000') {
                http_response_code(400);
                echo json_encode(["mensaje" => $errorInfo[2]]);
                $stmt->closeCursor();
                break;
            }
            
            $stmt->closeCursor();
            http_response_code(201);
            echo json_encode(["mensaje" => "Usuario registrado con exito"]);
        } catch (PDOException $e) {
            http_response_code(400);
            // Extraer el mensaje real del error del SP
            $mensaje = $e->getMessage();
            if (strpos($mensaje, 'El correo ya se encuentra registrado') !== false) {
                echo json_encode(["mensaje" => "El correo ya se encuentra registrado"]);
            } else {
                echo json_encode(["mensaje" => "Error al registrar: " . $mensaje]);
            }
        }
        break;

    case 'login':
        $datos = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("CALL sp_login_cliente(?)");
        $stmt->bindParam(1, $datos['nombre_usuario']);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($usuario && password_verify($datos['contrasena'], $usuario['contrasena'])) {
            if ($usuario['activo'] == 1) {
                unset($usuario['contrasena']);
                echo json_encode($usuario);
            } else {
                http_response_code(403);
                echo json_encode(["mensaje" => "Usuario desactivado"]);
            }
        } else {
            http_response_code(401);
            echo json_encode(["mensaje" => "Credenciales incorrectas"]);
        }
        break;

    // =====================================================================
    //  HU-20 (Dev 4): DETALLE DE ESPÉCIMEN
    // =====================================================================

    case 'obtener_especimen':
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
        $stmt = $db->prepare("CALL sp_obtener_especimen(?)");
        $stmt->bindParam(1, $codigo);
        $stmt->execute();
        $especimen = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($especimen) {
            echo json_encode($especimen);
        } else {
            http_response_code(404);
            echo json_encode(["mensaje" => "Espécimen no encontrado"]);
        }
        break;

    case 'imagenes_especimen':
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
        $stmt = $db->prepare("CALL sp_listar_imagenes_especimen(?)");
        $stmt->bindParam(1, $codigo);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'plantas_especimen':
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
        $stmt = $db->prepare("CALL sp_obtener_plantas_por_especimen(?)");
        $stmt->bindParam(1, $codigo);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'asociar_planta_especimen':

        $datos = json_decode(
            file_get_contents("php://input"),
            true
        );

        $stmt = $db->prepare(
            "CALL sp_asociar_planta_especimen(?, ?)"
        );

        $stmt->bindParam(
            1,
            $datos['codigo_especimen']
        );

        $stmt->bindParam(
            2,
            $datos['id_planta']
        );

        $stmt->execute();

        $stmt->closeCursor();

        echo json_encode([
            "mensaje" => "Asociación creada"
        ]);

        break;

    case 'eliminar_asociacion_planta':

        $datos = json_decode(
            file_get_contents("php://input"),
            true
        );

        $stmt = $db->prepare(
            "CALL sp_eliminar_asociacion_planta(?, ?)"
        );

        $stmt->bindParam(
            1,
            $datos['codigo_especimen']
        );

        $stmt->bindParam(
            2,
            $datos['id_planta']
        );

        $stmt->execute();

        $stmt->closeCursor();

        echo json_encode([
            "mensaje" => "Asociación eliminada"
        ]);

        break;

    // =====================================================================
    //  HU-21 (Dev 5): BÚSQUEDA POR NOMBRE CIENTÍFICO
    // =====================================================================

    case 'buscar_nombre':
        $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
        $offset   = isset($_GET['offset'])   ? (int)$_GET['offset'] : 0;
        $limit    = isset($_GET['limit'])    ? (int)$_GET['limit']  : 10;

        $stmt = $db->prepare("CALL sp_buscar_especimen_por_nombre(?, ?, ?)");
        $stmt->bindParam(1, $busqueda);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->bindParam(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    // =====================================================================
    //  HU-22 (Dev 5): BÚSQUEDA POR TAXONOMÍA + CASCADAS
    // =====================================================================

    case 'buscar_taxonomia':
        $id_orden      = isset($_GET['id_orden'])      ? (int)$_GET['id_orden']      : 0;
        $id_familia    = isset($_GET['id_familia'])    ? (int)$_GET['id_familia']    : 0;
        $id_subfamilia = isset($_GET['id_subfamilia']) ? (int)$_GET['id_subfamilia'] : 0;
        $id_genero     = isset($_GET['id_genero'])     ? (int)$_GET['id_genero']     : 0;
        $id_especie    = isset($_GET['id_especie'])    ? (int)$_GET['id_especie']    : 0;
        $offset        = isset($_GET['offset'])        ? (int)$_GET['offset']        : 0;
        $limit         = isset($_GET['limit'])         ? (int)$_GET['limit']         : 10;

        $stmt = $db->prepare("CALL sp_buscar_especimen_por_taxonomia(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $id_orden, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_familia, PDO::PARAM_INT);
        $stmt->bindParam(3, $id_subfamilia, PDO::PARAM_INT);
        $stmt->bindParam(4, $id_genero, PDO::PARAM_INT);
        $stmt->bindParam(5, $id_especie, PDO::PARAM_INT);
        $stmt->bindParam(6, $offset, PDO::PARAM_INT);
        $stmt->bindParam(7, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;


    case 'listar_ordenes':
        $stmt = $db->prepare("CALL sp_listar_ordenes()");
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'familias_por_orden':
        $id = isset($_GET['id_orden']) ? (int)$_GET['id_orden'] : 0;
        $stmt = $db->prepare("CALL sp_obtener_familias_por_orden(?)");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'subfamilias_por_familia':
        $id = isset($_GET['id_familia']) ? (int)$_GET['id_familia'] : 0;
        $stmt = $db->prepare("CALL sp_obtener_subfamilias_por_familia(?)");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'generos_por_familia':
        $id = isset($_GET['id_familia']) ? (int)$_GET['id_familia'] : 0;
        $stmt = $db->prepare("CALL sp_obtener_generos_por_familia(?)");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'generos_por_subfamilia':
        $id = isset($_GET['id_subfamilia']) ? (int)$_GET['id_subfamilia'] : 0;
        $stmt = $db->prepare("CALL sp_obtener_generos_por_subfamilia(?)");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'especies_por_genero':
        $id = isset($_GET['id_genero']) ? (int)$_GET['id_genero'] : 0;
        $stmt = $db->prepare("CALL sp_obtener_especies_por_genero(?)");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    // =====================================================================
    //  HU-23 (Dev 5): BÚSQUEDA POR PLANTA HOSPEDADORA
    // =====================================================================

    case 'buscar_plantas':
        $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
        $stmt = $db->prepare("CALL sp_listar_plantas(?)");
        $stmt->bindParam(1, $busqueda);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    case 'buscar_por_planta':
        $id_planta = isset($_GET['id_planta']) ? (int)$_GET['id_planta'] : 0;
        $offset    = isset($_GET['offset'])    ? (int)$_GET['offset']    : 0;
        $limit     = isset($_GET['limit'])     ? (int)$_GET['limit']     : 10;

        $stmt = $db->prepare("CALL sp_buscar_especimen_por_planta(?, ?, ?)");
        $stmt->bindParam(1, $id_planta, PDO::PARAM_INT);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->bindParam(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    // =====================================================================
    //  HU-24 (Dev 5): CARRITO DE BÚSQUEDA
    // =====================================================================

    case 'agregar_carrito':
        $datos = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("CALL sp_agregar_carrito(?, ?)");
        $stmt->bindParam(1, $datos['cedula']);
        $stmt->bindParam(2, $datos['codigo_especimen']);
        $stmt->execute();
        $stmt->closeCursor();
        echo json_encode(["mensaje" => "Agregado al carrito"]);
        break;

    case 'eliminar_carrito':
        $datos = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("CALL sp_eliminar_carrito(?, ?)");
        $stmt->bindParam(1, $datos['cedula']);
        $stmt->bindParam(2, $datos['codigo_especimen']);
        $stmt->execute();
        $stmt->closeCursor();
        echo json_encode(["mensaje" => "Eliminado del carrito"]);
        break;

    case 'obtener_carrito':
        $cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';
        $stmt = $db->prepare("CALL sp_obtener_carrito(?)");
        $stmt->bindParam(1, $cedula);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    // =====================================================================
    //  HU-25 (Dev 5): COMENTARIOS
    // =====================================================================

    case 'insertar_comentario':
        $datos = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("CALL sp_insertar_comentario(?, ?, ?)");
        $stmt->bindParam(1, $datos['codigo_especimen']);
        $stmt->bindParam(2, $datos['cedula']);
        $stmt->bindParam(3, $datos['comentario']);
        $stmt->execute();
        $stmt->closeCursor();
        http_response_code(201);
        echo json_encode(["mensaje" => "Comentario registrado"]);
        break;

    case 'listar_comentarios':
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
        $stmt = $db->prepare("CALL sp_listar_comentarios(?)");
        $stmt->bindParam(1, $codigo);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        echo json_encode($datos);
        break;

    // =====================================================================
    //  DEFAULT
    // =====================================================================

    default:
        http_response_code(400);
        echo json_encode(["mensaje" => "Acción no válida"]);
        break;
}
