// assets/js/form-validation.js
function initializeFormValidation(formId, options = {}) {
    $(formId).validate({
        errorElement: 'span',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        ...options
    });
}