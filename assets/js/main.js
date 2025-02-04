// assets/js/main.js
function initializeDataTable(tableId, config = {}) {
    const defaultConfig = {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    };

    return $(tableId).DataTable({...defaultConfig, ...config});
}