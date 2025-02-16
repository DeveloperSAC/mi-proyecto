'use strict';

class ProductoManager {
    constructor() {
        this.baseUrl = 'http://localhost/MIPROYECTO/backend/productos.php';
        this.categoriasUrl = 'http://localhost/MIPROYECTO/backend/categorias.php';
        this.initializeEventListeners();
        this.cargarProductos();
        this.cargarCategorias();
    }

    initializeEventListeners() {
        // Botón "Nuevo Producto"
        document.getElementById('btnAgregarProducto').addEventListener('click', () => {
            this.resetForm();
            this.openModal('Agregar Producto');
        });

        // Botón "Guardar"
        document.getElementById('btnGuardarProducto').addEventListener('click', () => {
            this.guardarProducto();
        });

        // Eventos de la tabla
        document.querySelector('#tablaProductos tbody').addEventListener('click', (e) => {
            const target = e.target.closest('.btn');
            if (!target) return;

            const id = target.dataset.id;
            if (target.classList.contains('btnEditar')) {
                this.editarProducto(id);
            } else if (target.classList.contains('btnEliminar')) {
                this.confirmarEliminacion(id);
            }
        });
    }

    async cargarProductos() {
        try {
            const response = await fetch(this.baseUrl);
            if (!response.ok) throw new Error('Error en la respuesta del servidor');

            const data = await response.json();
            if (data.status !== 'success') throw new Error(data.message);

            this.renderizarTabla(data.data);
        } catch (error) {
            console.error('Error al cargar productos:', error);
            this.mostrarError('No se pudieron cargar los productos');
        }
    }

    async cargarCategorias() {
        try {
            const response = await fetch(this.categoriasUrl);
            if (!response.ok) throw new Error('Error en la respuesta del servidor');

            const data = await response.json();
            if (data.status !== 'success') throw new Error(data.message);

            this.renderizarCategorias(data.data);
        } catch (error) {
            console.error('Error al cargar categorías:', error);
            this.mostrarError('No se pudieron cargar las categorías');
        }
    }

    renderizarTabla(productos) {
        const tbody = document.querySelector('#tablaProductos tbody');
        tbody.innerHTML = productos.map(prod => `
            <tr>
                <td>${this.escapeHtml(prod.id)}</td>
                <td>${this.escapeHtml(prod.nombre)}</td>
                <td>${this.escapeHtml(prod.categoria_nombre)}</td>
                <td>${this.escapeHtml(prod.descripcion || '')}</td>
                <td>${this.escapeHtml(prod.precio)}</td>
                <td>
                    <button class="btn btn-warning btn-sm btnEditar" data-id="${prod.id}">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-danger btn-sm btnEliminar" data-id="${prod.id}">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
        `).join('');
    }

    renderizarCategorias(categorias) {
        const select = document.getElementById('categoria_id');
        select.innerHTML = categorias.map(cat => `
            <option value="${cat.id}">${cat.nombre}</option>
        `).join('');
    }

    async guardarProducto() {
        try {
            const form = document.getElementById('formProducto');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const nombre = document.getElementById('nombre').value.trim();
            const categoria_id = document.getElementById('categoria_id').value;
            const descripcion = document.getElementById('descripcion').value.trim();
            const precio = document.getElementById('precio').value;

            if (!nombre || !categoria_id || !precio) {
                this.mostrarError('Todos los campos son requeridos');
                return;
            }

            const datos = {
                nombre: this.escapeHtml(nombre),
                categoria_id: this.escapeHtml(categoria_id),
                descripcion: this.escapeHtml(descripcion),
                precio: this.escapeHtml(precio)
            };

            if (this.currentId) {
                datos.id = this.currentId;
            }

            const response = await fetch(this.baseUrl, {
                method: this.currentId ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(datos)
            });

            const data = await response.json();
            if (data.status !== 'success') throw new Error(data.message);

            this.mostrarExito(data.message);
            this.cerrarModal();
            await this.cargarProductos();
        } catch (error) {
            console.error('Error al guardar producto:', error);
            this.mostrarError('No se pudo guardar el producto');
        }
    }

    async editarProducto(id) {
        try {
            const response = await fetch(`${this.baseUrl}?id=${id}`);
            if (!response.ok) throw new Error('Error al obtener el producto');

            const data = await response.json();
            if (data.status !== 'success') throw new Error(data.message);

            this.currentId = id;
            document.getElementById('nombre').value = data.data.nombre;
            document.getElementById('categoria_id').value = data.data.categoria_id;
            document.getElementById('descripcion').value = data.data.descripcion || '';
            document.getElementById('precio').value = data.data.precio;
            this.openModal('Editar Producto');
        } catch (error) {
            console.error('Error al editar producto:', error);
            this.mostrarError('No se pudo cargar el producto');
        }
    }

    async confirmarEliminacion(id) {
        try {
            const result = await Swal.fire({
                title: '¿Eliminar producto?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                const response = await fetch(this.baseUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });

                const data = await response.json();
                if (data.status !== 'success') throw new Error(data.message);

                this.mostrarExito('Producto eliminado');
                await this.cargarProductos();
            }
        } catch (error) {
            console.error('Error al eliminar producto:', error);
            this.mostrarError('No se pudo eliminar el producto');
        }
    }

    openModal(title) {
        document.getElementById('modalProductoLabel').textContent = title;
        const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
        modal.show();
    }

    cerrarModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalProducto'));
        if (modal) modal.hide();
    }

    resetForm() {
        const form = document.getElementById('formProducto');
        form.reset();
        form.classList.remove('was-validated');
        this.currentId = null;
    }

    escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return str
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    mostrarExito(mensaje) {
        Swal.fire({
            title: 'Éxito',
            text: mensaje,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }

    mostrarError(mensaje) {
        Swal.fire({
            title: 'Error',
            text: mensaje,
            icon: 'error'
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => new ProductoManager());