class CategoriaManager {
    constructor() {
        this.baseUrl = 'http://localhost/MIPROYECTO/backend/categorias.php';
        this.initializeEventListeners();
        this.cargarCategorias();
    }

    initializeEventListeners() {
        // Botón "Nueva Categoría"
        document.getElementById('btnAgregarCategoria').addEventListener('click', () => {
            this.resetForm();
            this.openModal('Agregar Categoría');
        });

        // Botón "Guardar"
        document.getElementById('btnGuardarCategoria').addEventListener('click', () => {
            this.guardarCategoria();
        });

        // Eventos de la tabla
        document.querySelector('#tablaCategorias tbody').addEventListener('click', (e) => {
            const target = e.target.closest('.btn');
            if (!target) return;

            const id = target.dataset.id;
            if (target.classList.contains('btnEditar')) {
                this.editarCategoria(id);
            } else if (target.classList.contains('btnEliminar')) {
                this.confirmarEliminacion(id);
            }
        });
    }

    async cargarCategorias() {
        try {
            const response = await fetch(this.baseUrl);
            if (!response.ok) throw new Error('Error en la respuesta del servidor');

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON válido');
            }

            const data = await response.json();
            if (data.status !== 'success') throw new Error(data.message);

            this.renderizarTabla(data.data);
        } catch (error) {
            console.error('Error al cargar categorías:', error);
            this.mostrarError('No se pudieron cargar las categorías');
        }
    }

    renderizarTabla(categorias) {
        const tbody = document.querySelector('#tablaCategorias tbody');
        tbody.innerHTML = categorias.map(cat => `
            <tr>
                <td>${this.escapeHtml(cat.id)}</td>
                <td>${this.escapeHtml(cat.nombre)}</td>
                <td>${this.escapeHtml(cat.descripcion || '')}</td>
                <td>
                    <button class="btn btn-warning btn-sm btnEditar" data-id="${cat.id}">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-danger btn-sm btnEliminar" data-id="${cat.id}">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
        `).join('');
    }

    async guardarCategoria() {
        try {
            const form = document.getElementById('formCategoria');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();

            if (!nombre) {
                this.mostrarError('El nombre es requerido');
                return;
            }

            const datos = {
                nombre: this.escapeHtml(nombre),
                descripcion: this.escapeHtml(descripcion)
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

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON válido');
            }

            const data = await response.json();
            if (data.status !== 'success') {
                throw new Error(data.message);
            }

            this.mostrarExito(data.message);
            this.cerrarModal();
            await this.cargarCategorias();
        } catch (error) {
            console.error('Error detallado:', error);
            this.mostrarError(`No se pudo guardar la categoría: ${error.message}`);
        }
    }

    async editarCategoria(id) {
        try {
            const response = await fetch(`${this.baseUrl}?id=${id}`);
            if (!response.ok) throw new Error('Error al obtener la categoría');

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('La respuesta no es JSON válido');
            }

            const data = await response.json();
            if (data.status !== 'success') throw new Error(data.message);

            this.currentId = id;
            document.getElementById('nombre').value = data.data.nombre;
            document.getElementById('descripcion').value = data.data.descripcion || '';
            this.openModal('Editar Categoría');
        } catch (error) {
            console.error('Error al editar categoría:', error);
            this.mostrarError('No se pudo cargar la categoría');
        }
    }

    async confirmarEliminacion(id) {
        try {
            const result = await Swal.fire({
                title: '¿Eliminar categoría?',
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

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('La respuesta no es JSON válido');
                }

                const data = await response.json();
                if (data.status !== 'success') throw new Error(data.message);

                this.mostrarExito('Categoría eliminada');
                await this.cargarCategorias();
            }
        } catch (error) {
            console.error('Error al eliminar categoría:', error);
            this.mostrarError('No se pudo eliminar la categoría');
        }
    }

    openModal(title) {
        document.getElementById('modalCategoriaLabel').textContent = title;
        const modalElement = document.getElementById('modalCategoria');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }

    cerrarModal() {
        const modalElement = document.getElementById('modalCategoria');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) modal.hide();
    }

    resetForm() {
        const form = document.getElementById('formCategoria');
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
            showConfirmButton: false,
            customClass: {
                input: 'swal2-input-accessible',
                image: 'swal2-image-accessible',
                select: 'swal2-select-accessible'
            },
            inputAttributes: {
                'aria-label': 'Campo de entrada',
            },
            imageAlt: 'Icono de éxito'
        });
    }

    mostrarError(mensaje) {
        Swal.fire({
            title: 'Error',
            text: mensaje,
            icon: 'error',
            customClass: {
                input: 'swal2-input-accessible',
                select: 'swal2-select-accessible'
            },
            inputAttributes: {
                'aria-label': 'Mensaje de error',
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => new CategoriaManager());