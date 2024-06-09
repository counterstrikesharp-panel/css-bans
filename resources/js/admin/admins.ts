import DataTable from 'datatables.net-dt';
import {formatDuration, calculateProgress} from '../utility/utility';
import  'datatables.net-fixedcolumns';
import 'datatables.net-responsive';
const dataTable = new DataTable("#adminsList", {
    "processing": true,
    responsive: true,
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
            "data": "ends", "width":"100px", "render": function (data, type, row, meta) {
                let progress = calculateProgress(row.created, row.ends);

                let progressBarClass = 'bg-warning';
                if (progress === 100) {
                    progressBarClass = 'bg-success';
                }
                else if (row.ends.includes("badge badge-primary")) {
                    progressBarClass = 'bg-danger';
                }

                progress = isNaN(progress) ? 100 : progress;

                return `
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated custom-progress ${progressBarClass}"
                    role="progressbar" style="width:  ${progress}%" aria-valuenow="${progress}"
                    aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>`;
            }
        }
    ]
});
