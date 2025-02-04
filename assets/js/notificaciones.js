// assets/js/notifications.js
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

function showNotification(type, message) {
    Toast.fire({
        icon: type,
        title: message
    });
}

function showLoading() {
    $('.loading-overlay').fadeIn(200);
}

function hideLoading() {
    $('.loading-overlay').fadeOut(200);
}