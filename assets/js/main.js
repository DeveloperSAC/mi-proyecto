// Función para obtener el total de categorías
function obtenerTotalCategorias() {
    fetch('/backend/categorias.php')
        .then(response => response.json())
        .then(data => {
            // Actualizar el contenido de la tarjeta de categorías
            document.getElementById('totalCategorias').textContent = data.length;
        })
        .catch(error => console.error('Error al obtener categorías:', error));
}

// Función para obtener el total de productos
function obtenerTotalProductos() {
    fetch('/backend/productos.php')
        .then(response => response.json())
        .then(data => {
            // Actualizar el contenido de la tarjeta de productos
            document.getElementById('totalProductos').textContent = data.length;
        })
        .catch(error => console.error('Error al obtener productos:', error));
}

// Llamar a las funciones al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    obtenerTotalCategorias();
    obtenerTotalProductos();
});