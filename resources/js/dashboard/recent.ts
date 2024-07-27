import axios from 'axios';
import {appendTableData, formatDuration, calculateProgress} from '../utility/utility';
import $ from "jquery";
let tableRows = null;
// Make a GET request to fetch mutes data
axios.get(recentMutesUrl)
    .then(response => {
        // Handle successful response
        appendTableData(constructTableRows(response.data), 'recentMutes');
    })
    .catch(error => {
        // Handle error
        console.error('Error:', error);
    });

axios.get(recentBansUrl)
    .then(response => {
        // Handle successful response
        appendTableData(constructTableRows(response.data), 'recentBans');
    })
    .catch(error => {
        // Handle error
        console.error('Error:', error);
    });

// Function to construct table rows dynamically
function constructTableRows(data: any[]): string {
    let html = '';

    data.forEach((item, index) => {
        let progress = calculateProgress(item.created, item.ends);

        let progressBarClass = 'bg-warning';
        if (progress === 100) {
            progressBarClass = 'bg-success';
        }
        else if (item.status === "UNBANNED" || item.status === "UNMUTED") {
            progressBarClass = 'bg-primary';
        }
        else if (item.duration === 0) {
            progressBarClass = 'bg-danger';
        }

        progress = isNaN(progress) ? 100 : progress;
        html += `
      <tr>
        <td><a href="https://steamcommunity.com/profiles/${item.player_steamid}/">${truncatePlayerName(item.player_name)}</a></td>
        <td><a href="https://steamcommunity.com/profiles/${item.admin_steamid}/">${truncatePlayerName(item.admin_name)}</a></td>
        <td>${formatDuration(item.created)}</td>
        <td>${item.ends}</td>
        <td>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated custom-progress ${progressBarClass}"
                role="progressbar" style="width:  ${progress}%" aria-valuenow="${progress}"
                aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </td>
      </tr>
    `;
    });

    return html;
}

function truncatePlayerName(playerName: string): string {
    if (playerName === null) {
        return "Unknown";
    } else if (playerName.length > 19) {
        return playerName.substring(0, 16) + '...';
    } else {
        return playerName;
    }
}
