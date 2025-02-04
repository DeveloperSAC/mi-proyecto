$(document).ready(function() {
    // Cargar productos y categorías al iniciar
    cargarProductos();
    cargarCategorias();
    
    // Manejar envío del formulario
    $('#formProducto').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../backend/productos.php',
            method: 'POST',
            data: {
                accion: 'crear',
                nombre: $('#nombre').val(),
                categoria_id: $('#categoria_id').val(),
                descripcion: $('#descripcion').val(),
                precio: $('#precio').val()
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Producto creado exitosamente');
                    cargarProductos();
                    $('#formProducto')[0].reset();
                } else {
                    alert('Error al crear producto');
                }
            }
        });
    });
});

function cargarProductos() {
    $.ajax({
        url: '../backend/productos.php',
        method: 'POST',
        data: { accion: 'obtenerTodos' },
        success: function(data) {
            const productos = JSON.parse(data);
            let html = '<table class="table">';
            html += '<thead><tr><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Acciones</th></tr></thead><tbody>';
            
            productos.forEach(function(producto) {
                html += `<tr>
                    <td>${producto.nombre}</td>
                    <td>${producto.categoria_nombre}</td>
                    <td>$${producto.precio}</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>`;
            });
            
            html += '</tbody></table>';
            $('#listaProductos').html(html);
        }
    });
}

function cargarCategorias() {
    $.ajax({
        url: '../backend/categorias.php',
        method: 'GET',
        success: function(data) {
            const categorias = JSON.parse(data);
            let html = '<option value="">Seleccione una categoría</option>';
            
            categorias.forEach(function(categoria) {
                html += `<option value="${categoria.id}">${categoria.nombre}</option>`;
            });
            
            $('#categoria_id').html(html);
        }
    });
}