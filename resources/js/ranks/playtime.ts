import DataTable from 'datatables.net-dt';
import 'datatables.net-responsive';
let dataTable = null;
function loadPlayTime() {
    dataTable = new DataTable("#playTimeList", {
        "processing": true,
        "serverSide": true,
        "responsive": true,
        pageLength: 25,
        "ajax": {
            "url": playTimeListUrl,
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
        order: [[2, 'desc']],
        "columns": [
            {"data": "position"},
            {
                "data": "name", "render": function (data, type, row, meta) {
                    const truncatedName = truncatePlayerName(data);
                    return `<div class="ranksList"><span class="list-profile"><a target="_blank" href="${row.profile}"><i class="fas fa-external-link-alt rank-profile"></i></a><img src="${row.avatar}" /><a href="https://steamcommunity.com/profiles/${row.player_steamid}">${truncatedName}</a></span><p class="text-muted mb-0">${window.translations.lastSeen}: <span class="badge badge-light-info rounded-pill d-inline">${row.last_seen}</span></p></div>`;
                }
            },
            {"data": "playtime"},
            {"data": "kills"},
            {"data": "deaths"},
            {"data": "assists"},
            {"data": "headshots"},
            {"data": "rounds_ct"},
            {"data": "rounds_t"},
            {"data": "rounds_overall"},
            {"data": "games_won"},
            {"data": "games_lost"}
        ]
    });
}
loadPlayTime();

function truncatePlayerName(playerName: string): string {
    if (playerName === null) {
        return "Unknown";
    } else if (playerName.length > 15) {
        return playerName.substring(0, 12) + '...';
    } else {
        return playerName;
    }
}
