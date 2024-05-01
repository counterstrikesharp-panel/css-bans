import DataTable from 'datatables.net-dt';
let dataTable = null;
function loadGroups() {
    dataTable = new DataTable("#groupsList", {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": groupsListUrl,
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            "type": "POST",
            "dataType": "json"
        },
        "language": {
            "search": "Search by group",
            'processing': '<div class="spinner"></div>'

        },
        order: [[0, 'desc']],
        "columns": [
            {"data": "id"},
            {"data": "name"},
            {"data": "flags"},
        ]
    });
}
loadGroups();

