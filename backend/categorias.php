<?php
require_once '../class/autoload.php';
require_once '../class/categorias.php';

// Obtenemos la conexión
$conexion = Conexion::getInstance();
$pdo = $conexion->getConnection();

// Creamos la instancia de Categorias
$categorias = new Categorias($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    switch($accion) {
        case 'crear':
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            
            if ($categorias->crear($nombre, $descripcion)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            break;
    }
}
?>