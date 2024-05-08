import DataTable from 'datatables.net-dt';
let dataTable = null;
function loadRanks() {
    dataTable = new DataTable("#ranksList", {
        "processing": true,
        "serverSide": true,
        // pageLength: 50,
        "ajax": {
            "url": ranksListUrl,
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            "type": "POST",
            "dataType": "json"
        },
        "language": {
            "search": "Search by player steam or name:",
            'processing': '<div class="spinner"></div>'

        },
        order: [[1, 'desc']],
        "columns": [
            {
                "data": "name" , "render": function (data, type, row, meta) {
                    return `<div class="ranksList"><span class="list-profile"><img src="${row.avatar}" /><a href="https://steamcommunity.com/profiles/${row.player_steamid}">${data}</a></span><p class="text-muted mb-0">Last seen: <span class="badge badge-info rounded-pill d-inline">${row.last_seen}</span></p></div>`;
                }
            },
            {
                "data": "points", "render": function (data, type, row, meta) {
                    return `<span class="badge badge-success rounded-pill d-inline">${row.points}</span>`;
                }
            },
            {"data": "rank"},
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
loadRanks();



