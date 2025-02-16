<?php
include_once 'conexion.php';

// Consultar todos los pedidos y sus detalles
$sql = "SELECT p.id AS pedido_id, u.username AS usuario, p.total, p.estado_id, p.fecha_pedido, dp.producto_id, pr.nombre AS producto, dp.cantidad, dp.precio
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN detalles_pedido dp ON p.id = dp.pedido_id
        JOIN productos pr ON dp.producto_id = pr.id";

$result = $conexion->query($sql);

// Mostrar los pedidos en una tabla
echo "<table>";
echo "<tr><th>Pedido ID</th><th>Usuario</th><th>Total</th><th>Estado</th><th>Fecha</th><th>Producto</th><th>Cantidad</th><th>Precio</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['pedido_id']}</td>
            <td>{$row['usuario']}</td>
            <td>{$row['total']}</td>
            <td>{$row['estado_id']}</td>
            <td>{$row['fecha_pedido']}</td>
            <td>{$row['producto']}</td>
            <td>{$row['cantidad']}</td>
            <td>{$row['precio']}</td>
          </tr>";
}

echo "</table>";
?>
