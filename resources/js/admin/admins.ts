import DataTable from 'datatables.net-dt';
import {formatDuration, calculateProgress} from '../utility/utility';

const dataTable = new DataTable("#adminsList", {
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
        {"data": "hostnames", "width":"300px"},
        {"data": "created"},
        {"data": "ends"},
        {"data": "actions", "width": "200px"},
        {
            "data": "progress", "render": function (data, type, row, meta) {
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
