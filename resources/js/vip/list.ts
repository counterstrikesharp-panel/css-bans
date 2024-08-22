import DataTable from 'datatables.net-dt';
import 'datatables.net-fixedcolumns';
import 'datatables.net-responsive';

let dataTable = null;

function loadVIPs() {
    dataTable = new DataTable("#vipList", {
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: {
            url: vipListUrl,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            dataType: 'json'
        },
        language: {
            "search": window.translations.searchByPlayernameAndSteamid,
            processing: '<div class="spinner"></div>'
        },
        order: [[3, 'desc']],
        columns: [
            {
                "data": "name", "render": function (data, type, row, meta) {
                    return `<span class="list-profile"><img src="${row.avatar}" />${row.steam_profile}</span><p class="text-muted mb-0">${window.translations.lastSeen}: <span class="badge badge-light-info rounded-pill d-inline">${row.last_seen}</span></p>`;
                }
            },
            {data: 'player_nick'},
            { data: 'sid' },
            { data: 'group' },
            { data: 'expires' },
            { data: 'action'}
        ]
    });
}

loadVIPs();
