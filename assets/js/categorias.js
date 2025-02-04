$(document).ready(function() {
    // Cargar categorías al iniciar
    cargarCategorias();
    
    // Manejar envío del formulario
    $('#formCategoria').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../backend/categorias.php',
            method: 'POST',
            data: {
                accion: 'crear',
                nombre: $('#nombre').val(),
                descripcion: $('#descripcion').val()
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Categoría creada exitosamente');
                    cargarCategorias();
                    $('#formCategoria')[0].reset();
                } else {
                    alert('Error al crear categoría');
                }
            }
        });
    });
});

function cargarCategorias() {
    $.ajax({
        url: '../backend/categorias.php',
        method: 'GET',
        success: function(data) {
            $('#listaCategorias').html(data);
        }
    });
}