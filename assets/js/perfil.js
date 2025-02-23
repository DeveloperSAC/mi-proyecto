document.addEventListener('DOMContentLoaded', function () {
    const formPerfil = document.getElementById('formPerfil');

    if (formPerfil) {
        // Cargar datos del usuario (simulado o desde el backend)
        cargarDatosUsuario();

        // Escuchar el evento de envío del formulario
        formPerfil.addEventListener('submit', function (e) {
            e.preventDefault(); // Evita que el formulario se envíe

            // Validar contraseñas
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden.',
                });
                return;
            }

            // Obtener los valores del formulario
            const datosUsuario = {
                nombre: document.getElementById('nombre').value,
                apellido: document.getElementById('apellido').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                password: password, // La contraseña ya se validó
            };

            // Enviar los datos al backend
            guardarDatosUsuario(datosUsuario);
        });
    }

    // Función para cargar datos del usuario (simulado o desde el backend)
    function cargarDatosUsuario() {
        // Simulación de datos cargados desde el backend
        const usuario = {
            nombre: 'Juan',
            apellido: 'Pérez',
            email: 'juan.perez@example.com',
            telefono: '123456789',
        };

        // Llenar el formulario con los datos del usuario
        document.getElementById('nombre').value = usuario.nombre;
        document.getElementById('apellido').value = usuario.apellido;
        document.getElementById('email').value = usuario.email;
        document.getElementById('telefono').value = usuario.telefono;
    }

    // Función para guardar datos del usuario en el backend
    function guardarDatosUsuario(datos) {
        // URL del endpoint del backend (ajusta la ruta según tu proyecto)
        const url = 'http://localhost/MIPROYECTO/api/guardar_perfil.php';

        // Opciones para la petición Fetch
        const opciones = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                // Si usas JWT para autenticación, incluye el token aquí
                // 'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
            body: JSON.stringify(datos), // Convertir los datos a JSON
        };

        // Enviar la petición al backend
        fetch(url, opciones)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al guardar los datos');
                }
                return response.json(); // Parsear la respuesta JSON
            })
            .then(data => {
                // Mostrar notificación de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Perfil actualizado!',
                    text: data.mensaje || 'Tus cambios se han guardado correctamente.',
                }).then(() => {
                    // Limpiar el formulario después de guardar
                    formPerfil.reset();
                });

                // Aquí puedes hacer algo con la respuesta del backend, si es necesario
                console.log('Respuesta del backend:', data);
            })
            .catch(error => {
                // Mostrar notificación de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al guardar los cambios. Por favor, inténtalo de nuevo.',
                });
                console.error('Error:', error);
            });
    }
});