import DataTable from 'datatables.net-dt';
import {formatDuration, calculateProgress} from '../utility/utility';
import 'datatables.net-fixedcolumns';
import 'datatables.net-responsive';
let dataTable = null;
function loadBans() {
    let columns = [
        {"data": "id"},
        {
            "data": "player_name", "render": function (data, type, row, meta) {
                return `<span class="list-profile"><img src="${row.avatar}" /><a href="https://steamcommunity.com/profiles/${row.player_steamid}">${data}</a></span>`;
            }
        },
        {"data": "player_ip"},
        {
            "data": "admin_steamid", "render": function (data, type, row, meta) {
                if (data !== 'Console') {
                    return `<a href="https://steamcommunity.com/profiles/${data}">${row.admin_name}</a>`;
                } else {
                    return data;
                }
            }
        },
        {"data": "reason"},
        {"data": "duration"},
        {"data": "ends"},
        {
            "data": "created", "render": function (data, type, row, meta) {
                return formatDuration(data);
            }
        },
        {"data": "server_id"},
        {"data": "status"},
        {
            "data": "action", "render": function (data, type, row, meta) {
                return '<div class="action-container">' + data + '</div>';
            }
        },
        {
            "data": "duration", "render": function (data, type, row, meta) {
                let progress = calculateProgress(row.created, row.ends);

                let progressBarClass = 'bg-warning';
                if (row.status.includes("badge badge-primary")) {
                    progressBarClass = 'bg-primary';
                    progress = 100;
                } else if (row.duration.includes("badge badge-danger")) {
                    progressBarClass = 'bg-danger';
                } else if (progress === 100) {
                    progressBarClass = 'bg-success';
                }
                return `
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated custom-progress ${progressBarClass}"
                    role="progressbar" style="width: ${progress}%" aria-valuenow="${progress}"
                    aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>`;
            }
        }
    ];

    if (!isSuperAdmin) {
        columns = columns.filter(column => column.data !== "player_ip");
    }
    /*if (!hasBanPermission || !hasWebBanEditPermissions || !hasUnBanPermission) {
        columns = columns.filter(column => column.data !== "action");
    }*/

    dataTable = new DataTable("#bansList", {
        "processing": true,
        responsive: true,
        "serverSide": true,
        "ajax": {
            "url": bansListUrl,
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            "type": "POST",
            "dataType": "json"
        },
        "language": {
            "search": window.translations.searchByPlayernameAndSteamid,
            'processing': '<div class="spinner"></div>'
        },
        order: [[0, 'desc']],
        "columns": columns
    });
}

loadBans();

$(document).on('click', '.unban-btn', function () {
    let playerSteamid = $(this).data('player-steamid');
    $.ajax({
        url: getPlayerUnBanUrl(playerSteamid),
        type: 'PUT',
        dataType: 'json',
        data: {
            playerSteamid: playerSteamid
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Snackbar.show({
                text: 'Player unbanned successfully.',
                actionTextColor: '#fff',
                backgroundColor: '#00ab55',
                pos: 'top-center'
            });
            dataTable.ajax.reload();
        },
        error: function(xhr, status, error) {
            Snackbar.show({
                text: 'Failed to unban player!',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                pos: 'top-center'
            });
        }
    });
});