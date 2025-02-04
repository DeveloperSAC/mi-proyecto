<!DOCTYPE html>
<html lang="es">
<head>
    <!-- ... códigos anteriores ... -->
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Navbar mejorado -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tasks me-2"></i>
                Sistema de Gestión
            </a>
            <!-- ... resto del navbar ... -->
        </div>
    </nav>

    <!-- Dashboard Cards -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-folder me-2"></i>
                            Categorías
                        </h5>
                        <p class="card-text" id="totalCategorias">Cargando...</p>
                    </div>
                </div>
            </div>
            <!-- ... más cards ... -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/notifications.js"></script>
    <script src="assets/js/form-validation.js"></script>
    <script src="assets/js/filters.js"></script>
</body>
</html>