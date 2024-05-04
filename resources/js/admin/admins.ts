import DataTable from 'datatables.net-dt';
import {formatDuration, calculateProgress} from '../utility/utility';
import  'datatables.net-fixedcolumns'

const dataTable = new DataTable("#adminsList", {
    fixedColumns: {
        start: 0,
        end: 3,
    },
    scrollX: true,
    scrollY: "800px",
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": adminListUrl,
        "headers": {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        },
        "type": "POST",
        "dataType": "json"
    },
    "language": {
        "search": "Search by admin name:",
        'processing': '<div class="spinner"></div>'
    },
    "columns": [
        {"data": "id"},
        {
            "data": "player_name", "render": function (data, type, row, meta) {
                return `<a href="https://steamcommunity.com/profiles/${row.player_steamid}">${data}</a>`;
            }
        },
        {"data": "flags"},
        {"data": "hostnames", "width":"50px"},
        {"data": "created"},
        {"data": "ends"},
        {
            "data": "actions", "width": "200px", "render": function (data, type, row, meta) {
                return '<div class="action-container">' + data + '</div>';
            }
        },
        {
            "data": "progress", "width":"100px", "render": function (data, type, row, meta) {
                const progress = calculateProgress(row.created, row.ends);
                return `
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated custom-progress bg-success"
                    role="progressbar" style="width:  ${progress}%" aria-valuenow="${progress}"
                    aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>`;
            }
        }
    ]
});
