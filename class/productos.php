<?php
class Productos {
    private $pdo;
    
    public function __construct($conexion) {
        $this->pdo = $conexion;
    }
    
    public function crear($nombre, $categoria_id, $descripcion, $precio) {
        try {
            $sql = "INSERT INTO productos (nombre, categoria_id, descripcion, precio) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $categoria_id, $descripcion, $precio]);
        } catch (PDOException $e) {
            echo "Error al crear producto: " . $e->getMessage();
            return false;
        }
    }
    
    public function obtenerTodos() {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener productos: " . $e->getMessage();
            return [];
        }
    }
    
    // ... [Métodos similares a categorías para CRUD]
}
?>