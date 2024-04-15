import axios from 'axios';
import {appendTableData, formatDuration, calculateProgress} from '../utility/utility';
import $ from "jquery";
let tableRows = null;
// Make a GET request to fetch mutes data
axios.get(mutesListUrl)
    .then(response => {
        // Handle successful response
        appendTableData(constructTableRows(response.data), 'recentMutes');
    })
    .catch(error => {
        // Handle error
        console.error('Error:', error);
    });

axios.get(bansListUrl)
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
        const progress = calculateProgress(item.created, item.ends);
        html += `
      <tr>
        <td><a href="https://steamcommunity.com/profiles/${item.player_name}/">Profile</a></td>
        <td>${item.player_steamid}</td>
        <td>${formatDuration(item.created)}</td>
        <td>${item.ends}</td>
        <td>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated custom-progress bg-danger"
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





