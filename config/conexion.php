<?php
class Conexion {
    private static $instancia = null;
    private $conexion;

    private function __construct() {
        // Configuración de la conexión a la base de datos
        $host = 'localhost'; // Cambia si tu base de datos está en otro servidor
        $dbname = 'miproyecto'; // Nombre de la base de datos
        $user = 'root'; // Usuario de la base de datos
        $password = ''; // Contraseña de la base de datos

        try {
            $this->conexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Fetch como array asociativo
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage()); // Log del error
            throw new Exception("Error de conexión a la base de datos"); // Lanza una excepción en lugar de die()
        }
    }

    public static function getInstance() {
        if (self::$instancia === null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia;
    }

    public function getConnection() {
        return $this->conexion;
    }
}
?>