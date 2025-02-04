<?php
class Conexion {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = 'mysql:host=localhost;dbname=miproyecto';
        $username = 'root';
        $password = '';
        
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>