// assets/js/filters.js
class FilterManager {
    constructor(tableId) {
        this.table = $(tableId);
        this.filters = {};
    }

    addFilter(column, value) {
        this.filters[column] = value;
        this.applyFilters();
    }

    removeFilter(column) {
        delete this.filters[column];
        this.applyFilters();
    }

    applyFilters() {
        let query = '';
        for (let column in this.filters) {
            if (this.filters[column]) {
                query += `${column}:${this.filters[column]} `;
            }
        }
        this.table.DataTable().search(query.trim()).draw();
    }
}