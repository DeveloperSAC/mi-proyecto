<?php
class Categorias {
    private $pdo;
    
    public function __construct($conexion) {
        $this->pdo = $conexion;
    }
    
    public function crear($nombre, $descripcion) {
        try {
            $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion]);
        } catch (PDOException $e) {
            echo "Error al crear categoría: " . $e->getMessage();
            return false;
        }
    }
    
    // ... [Resto de métodos anteriormente mostrados]
}
?>