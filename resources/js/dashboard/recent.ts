import axios from 'axios';
import {appendTableData, formatDuration, calculateProgress} from '../utility/utility';
import $ from "jquery";
// Make a GET request to fetch mutes data
axios.get(recentMutesUrl)
    .then(response => {
        // Handle successful response
        appendTableData(constructListItems(response.data), 'recentMutes');
    })
    .catch(error => {
        // Handle error
        console.error('Error:', error);
    });

axios.get(recentBansUrl)
    .then(response => {
        // Handle successful response
        appendTableData(constructListItems(response.data), 'recentBans');
    })
    .catch(error => {
        // Handle error
        console.error('Error:', error);
    });

// Function to construct table rows dynamically
function constructListItems(data: any[]): string {
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
      <li class="list-group-item">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <img src="${item.avatar}" class="rounded-circle me-3" style="width:40px;height:40px;" />
            <div>
              <a href="https://steamcommunity.com/profiles/${item.player_steamid}/">${truncatePlayerName(item.player_name)}</a>
              <p class="mb-0 small">${formatDuration(item.created)} - ${item.ends}</p>
              <p class="mb-0 small">Admin: <a href="https://steamcommunity.com/profiles/${item.admin_steamid}/">${truncatePlayerName(item.admin_name)}</a></p>
            </div>
          </div>
        </div>
        <div class="progress mt-2" style="height:6px;">
          <div class="progress-bar progress-bar-striped progress-bar-animated custom-progress ${progressBarClass}"
               role="progressbar" style="width:  ${progress}%" aria-valuenow="${progress}"
               aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </li>
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
