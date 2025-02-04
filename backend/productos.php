<?php
require_once '../class/autoload.php';
require_once '../class/productos.php';

$conexion = Conexion::getInstance();
$pdo = $conexion->getConnection();
$productos = new Productos($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    switch($accion) {
        case 'crear':
            $nombre = $_POST['nombre'] ?? '';
            $categoria_id = $_POST['categoria_id'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? 0;
            
            if ($productos->crear($nombre, $categoria_id, $descripcion, $precio)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            break;
            
        case 'obtenerTodos':
            $lista = $productos->obtenerTodos();
            echo json_encode($lista);
            break;
    }
}
?>