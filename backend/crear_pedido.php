<?php
include_once 'conexion.php'; // Incluir archivo de conexión a la base de datos

// Obtener datos del pedido desde el formulario
$usuario_id = $_POST['usuario_id']; // Asegúrate de recibir el id del usuario
$total = $_POST['total']; // Total del pedido
$estado_id = 1; // Estado del pedido (por ejemplo, 'En proceso')

// Crear la consulta para insertar el pedido en la tabla "pedidos"
$sql = "INSERT INTO pedidos (usuario_id, total, estado_id, fecha_pedido) 
        VALUES (?, ?, ?, NOW())";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iii", $usuario_id, $total, $estado_id);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Obtener el ID del pedido insertado
    $pedido_id = $stmt->insert_id;
    // Luego, agregar los productos a la tabla "detalles_pedido"
    foreach ($_POST['productos'] as $producto) {
        // Cada producto debe ser un array con [producto_id, cantidad, precio]
        $producto_id = $producto['producto_id'];
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];

        // Insertar en "detalles_pedido"
        $sql_detalles = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio) 
                         VALUES (?, ?, ?, ?)";
        $stmt_detalles = $conexion->prepare($sql_detalles);
        $stmt_detalles->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
        $stmt_detalles->execute();
        
        // Actualizar el stock del producto
        $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
        $stmt_stock = $conexion->prepare($sql_stock);
        $stmt_stock->bind_param("ii", $cantidad, $producto_id);
        $stmt_stock->execute();
    }
    echo "Pedido realizado con éxito.";
} else {
    echo "Error al crear el pedido.";
}
?>
