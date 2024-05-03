import DataTable from 'datatables.net-dt';
import {formatDuration, calculateProgress} from '../utility/utility';

let dataTable = null;

loadMutes();
function loadMutes() {
    dataTable = new DataTable("#mutesList", {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": mutesListUrl,
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            "type": "POST",
            "dataType": "json"
        },
        "language": {
            "search": "Search by player steamid:",
            'processing': '<div class="spinner"></div>'

        },
        order: [[0, 'desc']],
        "columns": [
            {"data": "id"},
            {
                "data": "player_name", "render": function (data, type, row, meta) {
                    return `<a href="https://steamcommunity.com/profiles/${row.player_steamid}">${truncatePlayerName(data)}</a>`;
                }
            },
            {
                "data": "admin_steamid", "render": function (data, type, row, meta) {
                    if(data != 'Console')
                        return `<a href="https://steamcommunity.com/profiles/${data}">${row.admin_name}</a>`;
                    else
                        return data;
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
            {"data": "action", "width": "200px"},
            {
                "data": "duration", "render": function (data, type, row, meta) {
                    const progress = calculateProgress(row.created, row.ends);
                    return `
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated custom-progress bg-warning"
                    role="progressbar" style="width:  ${progress}%" aria-valuenow="${progress}"
                    aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>`
                }
            }
        ]
    });
}
$(document).on('click', '.unmute-btn', function() {
    let playerSteamid = $(this).data('player-steamid');
    $.ajax({
        url: getPlayerUnMuteUrl(playerSteamid),
        type: 'PUT',
        dataType: 'json',
        data: {
            playerSteamid: playerSteamid
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            toastr.success('Player unmuted successfully.');
            dataTable.ajax.reload();
        },
        error: function(xhr, status, error) {
            toaster.error('Failed to unmute player!');
        }
    });
});

function truncatePlayerName(playerName: string): string {
    if (playerName.length > 19) {
        return playerName.substring(0, 16) + '...';
    } else {
        return playerName;
    }
}