<?php
class Categorias {
    private $pdo;
    private $logger;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->logger = new Logger();
    }

    public function obtenerTodas() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, nombre, descripcion, creado_en, actualizado_en
                FROM categorias 
                WHERE activo = 1 
                ORDER BY nombre ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error("Error al obtener categorías: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorId($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, nombre, descripcion 
                FROM categorias 
                WHERE id = :id AND activo = 1
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error("Error al obtener categoría por ID: " . $e->getMessage());
            return false;
        }
    }

    public function crear($nombre, $descripcion) {
        try {
            if ($this->existeNombre($nombre)) {
                return ['status' => 'error', 'message' => 'Ya existe una categoría con ese nombre'];
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO categorias (nombre, descripcion, creado_en, activo)
                VALUES (:nombre, :descripcion, NOW(), 1)
            ");
            
            $resultado = $stmt->execute([
                'nombre' => trim($nombre),
                'descripcion' => trim($descripcion)
            ]);

            return [
                'status' => $resultado ? 'success' : 'error',
                'message' => $resultado ? 'Categoría creada exitosamente' : 'Error al crear la categoría'
            ];
        } catch (PDOException $e) {
            $this->logger->error("Error al crear categoría: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error interno del servidor'];
        }
    }

    public function actualizar($id, $nombre, $descripcion) {
        try {
            if (!$this->existeId($id)) {
                return ['status' => 'error', 'message' => 'La categoría no existe'];
            }

            if ($this->existeNombreExceptoId($nombre, $id)) {
                return ['status' => 'error', 'message' => 'Ya existe otra categoría con ese nombre'];
            }

            $stmt = $this->pdo->prepare("
                UPDATE categorias 
                SET nombre = :nombre, 
                    descripcion = :descripcion, 
                    actualizado_en = NOW()
                WHERE id = :id AND activo = 1
            ");
            
            $resultado = $stmt->execute([
                'id' => $id,
                'nombre' => trim($nombre),
                'descripcion' => trim($descripcion)
            ]);

            return [
                'status' => $resultado ? 'success' : 'error',
                'message' => $resultado ? 'Categoría actualizada exitosamente' : 'Error al actualizar la categoría'
            ];
        } catch (PDOException $e) {
            $this->logger->error("Error al actualizar categoría: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error interno del servidor'];
        }
    }

    public function eliminar($id) {
        try {
            if (!$this->existeId($id)) {
                return ['status' => 'error', 'message' => 'La categoría no existe'];
            }

            $stmt = $this->pdo->prepare("
                UPDATE categorias 
                SET activo = 0, 
                    actualizado_en = NOW()
                WHERE id = :id
            ");
            
            $resultado = $stmt->execute(['id' => $id]);

            return [
                'status' => $resultado ? 'success' : 'error',
                'message' => $resultado ? 'Categoría eliminada exitosamente' : 'Error al eliminar la categoría'
            ];
        } catch (PDOException $e) {
            $this->logger->error("Error al eliminar categoría: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error interno del servidor'];
        }
    }

    private function existeNombre($nombre) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM categorias 
                WHERE LOWER(nombre) = LOWER(:nombre) 
                AND activo = 1
            ");
            $stmt->execute(['nombre' => trim($nombre)]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            $this->logger->error("Error al verificar existencia de nombre: " . $e->getMessage());
            return false;
        }
    }

    private function existeId($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM categorias 
                WHERE id = :id 
                AND activo = 1
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            $this->logger->error("Error al verificar existencia de ID: " . $e->getMessage());
            return false;
        }
    }

    private function existeNombreExceptoId($nombre, $id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM categorias 
                WHERE LOWER(nombre) = LOWER(:nombre) 
                AND id != :id 
                AND activo = 1
            ");
            $stmt->execute([
                'nombre' => trim($nombre),
                'id' => $id
            ]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            $this->logger->error("Error al verificar existencia de nombre excepto ID: " . $e->getMessage());
            return false;
        }
    }
}