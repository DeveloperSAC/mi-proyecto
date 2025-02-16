<?php
session_start();
require_once '../config/conexion.php';
require_once '../class/autoload.php';

// Verificar si existe una sesión activa
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión</title>
    
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/estilos.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 text-center">
                <h1><i class="fas fa-tasks"></i> Bienvenido al Sistema de Gestión</h1>
                <p class="lead">Seleccione una opción para comenzar</p>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <a href="productos.html" class="text-decoration-none">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fas fa-box fa-3x mb-3"></i>
                                    <h5 class="card-title">Productos</h5>
                                    <p class="card-text">Gestionar productos</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="categorias.html" class="text-decoration-none">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fas fa-folder fa-3x mb-3"></i>
                                    <h5 class="card-title">Categorías</h5>
                                    <p class="card-text">Gestionar categorías</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="home.html" class="text-decoration-none">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                    <h5 class="card-title">Dashboard</h5>
                                    <p class="card-text">Ver estadísticas</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
