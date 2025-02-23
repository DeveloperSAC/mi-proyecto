<?php
// Habilitar CORS (para permitir peticiones desde el frontend)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir la conexión a la base de datos
require_once '../config/conexion.php';

try {
    // Obtener la instancia de la conexión
    $conexion = Conexion::getInstance()->getConnection();

    // Obtener los datos enviados desde el frontend
    $data = json_decode(file_get_contents("php://input"));

    // Verificar que los datos no estén vacíos
    if (
        !empty($data->nombre) &&
        !empty($data->apellido) &&
        !empty($data->email) &&
        !empty($data->telefono) &&
        !empty($data->password)
    ) {
        // Asignar los datos a variables
        $nombre = $data->nombre;
        $apellido = $data->apellido;
        $email = $data->email;
        $telefono = $data->telefono;
        $password = password_hash($data->password, PASSWORD_DEFAULT); // Encriptar la contraseña

        // Verificar si el correo ya está registrado
        $query = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Respuesta si el correo ya está registrado
            http_response_code(400);
            echo json_encode(array("mensaje" => "El correo electrónico ya está registrado."));
        } else {
            // Insertar los datos en la base de datos
            $query = "INSERT INTO usuarios (nombre, apellido, email, telefono, password, created_at) VALUES (:nombre, :apellido, :email, :telefono, :password, NOW())";
            $stmt = $conexion->prepare($query);

            // Bind parameters
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':password', $password);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Respuesta de éxito
                http_response_code(201);
                echo json_encode(array("mensaje" => "Perfil guardado correctamente."));
            } else {
                // Respuesta de error
                http_response_code(503);
                echo json_encode(array("mensaje" => "Error al guardar el perfil."));
            }
        }
    } else {
        // Respuesta si los datos están incompletos
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos."));
    }
} catch (Exception $e) {
    // Manejar errores de conexión o ejecución
    http_response_code(500);
    echo json_encode(array("mensaje" => "Error en el servidor: " . $e->getMessage()));
}
?>