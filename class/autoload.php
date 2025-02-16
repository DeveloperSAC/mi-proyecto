<?php
class Conexion {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Configuración de la conexión (podría estar en un archivo config.php)
        $config = [
            'host' => 'localhost',
            'dbname' => 'miproyecto',
            'username' => 'root',
            'password' => ''
        ];

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
        
        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
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