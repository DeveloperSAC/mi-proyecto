<?php
// Configuración de CORS y headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir dependencias
require_once '../config/conexion.php';
require_once '../class/categorias.php';
require_once '../class/logger.php';

// Inicializar respuesta por defecto
$response = [
    'status' => 'error',
    'message' => 'Método no permitido',
    'data' => null
];

try {
    // Obtener conexión a la base de datos
    $pdo = Conexion::conectar();
    
    // Crear instancia de Categorias
    $categorias = new Categorias($pdo);
    
    // Procesar la solicitud según el método HTTP
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (isset($_GET['id'])) {
                $categoria = $categorias->obtenerPorId($_GET['id']);
                if ($categoria) {
                    $response = [
                        'status' => 'success',
                        'data' => $categoria
                    ];
                } else {
                    $response['message'] = 'Categoría no encontrada';
                }
            } else {
                $lista = $categorias->obtenerTodas();
                if ($lista !== false) {
                    $response = [
                        'status' => 'success',
                        'data' => $lista
                    ];
                } else {
                    $response['message'] = 'Error al obtener categorías';
                }
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $response['message'] = 'JSON inválido';
                break;
            }
            
            if (isset($data['nombre'])) {
                $result = $categorias->crear(
                    $data['nombre'],
                    $data['descripcion'] ?? ''
                );
                $response = $result;
            } else {
                $response['message'] = 'Datos incompletos';
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $response['message'] = 'JSON inválido';
                break;
            }
            
            if (isset($data['id'], $data['nombre'])) {
                $result = $categorias->actualizar(
                    $data['id'],
                    $data['nombre'],
                    $data['descripcion'] ?? ''
                );
                $response = $result;
            } else {
                $response['message'] = 'Datos incompletos';
            }
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $response['message'] = 'JSON inválido';
                break;
            }
            
            if (isset($data['id'])) {
                $result = $categorias->eliminar($data['id']);
                $response = $result;
            } else {
                $response['message'] = 'ID no proporcionado';
            }
            break;
    }
} catch (Exception $e) {
    $logger = new Logger();
    $logger->error("Error en categorias.php: " . $e->getMessage());
    $response = [
        'status' => 'error',
        'message' => 'Error interno del servidor'
    ];
}

// Asegurar que los headers no se han enviado ya
if (!headers_sent()) {
    http_response_code($response['status'] === 'success' ? 200 : 400);
}

// Enviar respuesta
echo json_encode($response);
exit();