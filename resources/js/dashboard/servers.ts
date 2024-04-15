import axios from 'axios';
import {appendTableData, formatDuration} from '../utility/utility';
import {ServerInfo} from '../Interface/ServerInfo';
import {showLoader} from "../utility/utility";
import {hideLoader} from "../utility/utility";

// Make a GET request to fetch mutes data
axios.get(serversListUrl)
    .then(response => {
        // Handle successful response
        appendTableData(constructTableRows(response.data), 'serverList');
    })
    .catch(error => {
        // Handle error
        console.error('Error:', error);
    });

// Function to construct table rows dynamically
function constructTableRows(data: any[]): string {
    let html = '';

    data.forEach((item: ServerInfo, index) => {
        html += `
      <tr>
        <td>${item.name}</td>
        <td>
            <a href="#" class="view-players">
                <i class="fas fa-eye" data-server-id="${item.id}"></i>
            </a>
            ${item.players}
        </td>
        <td>${item.ip}</td>
        <td>${item.port}</td>
        <td>${item.map}</td>
        <td>${item.connect_button}</td>
      </tr>
    `;
    });

    return html;
}

// Add event listener for view players button
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('fa-eye')) {
        event.preventDefault();
        const serverId = event.target.dataset.serverId;
        if (serverId) {
            fetchPlayers(serverId);
        } else {
            console.error('Server ID not found.');
        }
    }
});

// Function to fetch players for a specific server
function fetchPlayers(serverId: string) {
    showLoader();
    const playersUrl = `/servers/${serverId}/players`;
    axios.get(playersUrl)
        .then(response => {
            $("#modalBody").html(response.data);
            $("#modal").modal('show');
            hideLoader();
        })
        .catch(error => {
            console.error('Error fetching players:', error);
        });
}







