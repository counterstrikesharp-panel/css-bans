import DataTable from 'datatables.net-dt';
import 'datatables.net-responsive';
let dataTable = null;
function loadGroups() {
    dataTable = new DataTable("#groupsList", {
        "processing": true,
        "serverSide": true,
        responsive: true,
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
            {
                "data": "actions", "width": "200px", "render": function (data, type, row, meta) {
                    return '<div class="action-container">' + data + '</div>';
                }
            },
        ]
    });
}
loadGroups();

