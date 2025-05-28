@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Activity Logs') }} - CSS-BANS
    </x-slot>
    @vite(['resources/scss/dark/assets/components/datatable.scss'])
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <link rel="stylesheet" href="{{asset('plugins/notification/snackbar/snackbar.min.css')}}">
        @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])
        <style>
            /* Enhanced Table Styles */
            .custom-table {
                border-collapse: separate;
                border-spacing: 0;
                width: 100%;
                background-color: #1a1d3a;
                border-radius: 6px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            .custom-table thead th {
                background-color: #1e2a4a;
                color: #e0e6ed;
                border-bottom: 2px solid #3b3f5c;
                font-weight: 600;
                padding: 12px 15px;
                text-transform: uppercase;
                font-size: 13px;
                letter-spacing: 0.5px;
                position: sticky;
                top: 0;
                z-index: 10;
            }
            
            .custom-table tbody tr {
                border-bottom: 1px solid #3b3f5c;
                transition: all 0.2s;
            }
            
            .custom-table tbody tr:last-child {
                border-bottom: none;
            }
            
            .custom-table tbody tr:hover {
                background-color: #1e263c;
            }
            
            .custom-table td {
                padding: 12px 15px;
                vertical-align: middle;
                color: #bfc9d4;
                font-size: 13px;
            }
            
            .filter-container {
                background: #191e3a;
                padding: 15px;
                border-radius: 6px;
                margin-bottom: 20px;
                border: 1px solid #3b3f5c;
            }
            
            .admin-tag, .target-tag {
                display: inline-block;
                border-radius: 30px;
                padding: 5px 10px;
                font-size: 11px;
                font-weight: 600;
                margin-right: 5px;
            }
            
            .admin-tag {
                background: #3b3f5c;
                color: #fff;
            }
            
            .target-tag {
                background: #4361ee;
                color: #fff;
            }
            
            .status-indicator {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                display: inline-block;
                margin-right: 8px;
            }
            
            .status-ban { background-color: #e7515a; }
            .status-unban { background-color: #8dbf42; }
            .status-mute { background-color: #e2a03f; }
            .status-unmute { background-color: #2196f3; }
            .status-edit { background-color: #25d5e4; }
            .status-create { background-color: #5c1ac3; }
            .status-delete { background-color: #e7515a; }
            
            .action-btn {
                background-color: #1b2e4b;
                color: #bfc9d4;
                border: none;
                border-radius: 4px;
                padding: 6px 12px;
                font-size: 12px;
                transition: all 0.2s;
                cursor: pointer;
            }
            
            .action-btn:hover {
                background-color: #25d5e4;
                color: #1b2e4b;
            }
            
            .pagination-container {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }
            
            .pagination {
                display: flex;
                list-style: none;
                padding: 0;
                margin: 0;
                background: #1a1d3a;
                border-radius: 6px;
                overflow: hidden;
            }
            
            .pagination li {
                border-right: 1px solid #3b3f5c;
            }
            
            .pagination li:last-child {
                border-right: none;
            }
            
            .pagination li a, .pagination li span {
                padding: 8px 15px;
                color: #bfc9d4;
                display: block;
                transition: all 0.2s;
            }
            
            .pagination li.active span {
                background-color: #4361ee;
                color: white;
            }
            
            .pagination li a:hover {
                background-color: #1e2a4a;
            }
            
            .no-data {
                padding: 50px 0;
                text-align: center;
                color: #888ea8;
                font-size: 14px;
            }
            
            /* Modal Styles */
            .custom-modal .modal-content {
                background-color: #1b2e4b;
                color: #bfc9d4;
                border: 1px solid #3b3f5c;
                border-radius: 6px;
            }
            
            .custom-modal .modal-header {
                border-bottom: 1px solid #3b3f5c;
                background-color: #1e2a4a;
                padding: 15px 20px;
            }
            
            .custom-modal .modal-footer {
                border-top: 1px solid #3b3f5c;
                background-color: #1e2a4a;
                padding: 12px 20px;
            }
            
            .custom-modal .modal-title {
                font-weight: 600;
                color: #e0e6ed;
            }
            
            .custom-modal .modal-body {
                padding: 20px;
            }
            
            .custom-modal .close {
                color: #bfc9d4;
                text-shadow: none;
                opacity: 0.8;
            }
            
            .custom-modal .close:hover {
                opacity: 1;
            }
            
            #details-content {
                background: #0e1726;
                color: #bfc9d4;
                padding: 15px;
                border-radius: 6px;
                max-height: 350px;
                overflow-y: auto;
                font-family: monospace;
                font-size: 13px;
                line-height: 1.5;
            }
            
            .cursor-pointer {
                cursor: pointer;
            }
            
            .badge.badge-info {
                background-color: #11cdef;
                color: #fff;
            }
            
            .badge.badge-secondary {
                background-color: #6c757d;
                color: #fff;
            }
            
            /* Enhanced modal for server details */
            .details-tabs {
                border-bottom: 1px solid #3b3f5c;
                margin-bottom: 15px;
                display: flex;
                overflow-x: auto;
                padding-bottom: 5px;
            }
            
            .details-tab {
                padding: 8px 12px;
                background-color: #1b2e4b;
                color: #bfc9d4;
                border: none;
                margin-right: 5px;
                border-radius: 4px 4px 0 0;
                font-size: 12px;
                cursor: pointer;
                white-space: nowrap;
            }
            
            .details-tab:hover {
                background-color: #232f5b;
            }
            
            .details-tab.active {
                background-color: #4361ee;
                color: white;
            }
            
            .server-detail-container {
                background: #0e1726;
                border-radius: 6px;
                padding: 15px;
                margin-top: 10px;
                max-height: 350px;
                overflow-y: auto;
            }
            
            .server-badge {
                background-color: #1b55e2;
                color: white;
                border-radius: 30px;
                padding: 3px 8px;
                font-size: 11px;
                font-weight: 600;
                margin-right: 5px;
            }
            
            .detail-section {
                margin-bottom: 10px;
            }
            
            .detail-section-title {
                font-weight: 600;
                color: #e0e6ed;
                margin-bottom: 5px;
                display: block;
            }
            
            .detail-row {
                display: flex;
                margin-bottom: 5px;
                font-size: 13px;
            }
            
            .detail-label {
                flex: 0 0 120px;
                font-weight: 600;
                color: #888ea8;
            }
            
            .detail-value {
                flex: 1;
                color: #e0e6ed;
                word-break: break-all;
            }
            
            .modal-xl {
                max-width: 90%;
            }
            
            .server-info {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
                padding: 8px;
                border-radius: 6px;
                background-color: #1e2a4a;
            }
            
            .server-icon {
                margin-right: 10px;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #4361ee;
                border-radius: 50%;
            }
            
            .server-name {
                flex-grow: 1;
                font-weight: 600;
            }
            
            .server-id {
                color: #888ea8;
            }
        </style>
    </x-slot>

    @if (session('success'))
        <x-alert type="success" :message="session('success')"/>
    @endif
    @if (session('error'))
        <x-alert type="danger" :message="session('error')"/>
    @endif

    <section class="mb-12">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>{{ __('Admin Activity Logs') }}</strong>
                </h5>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="filter-container mb-4">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="text-white">Start Date</label>
                                <input type="date" id="start_date" class="form-control form-control-sm bg-dark text-white">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="text-white">End Date</label>
                                <input type="date" id="end_date" class="form-control form-control-sm bg-dark text-white">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="text-white">Admin</label>
                                <select id="admin_filter" class="form-control form-control-sm bg-dark text-white">
                                    <option value="">All Admins</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label class="text-white">Action</label>
                                <select id="action_filter" class="form-control form-control-sm bg-dark text-white">
                                    <option value="">All Actions</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="apply_filters" class="btn btn-sm btn-primary mr-2">Apply Filters</button>
                            <button id="clear_filters" class="btn btn-sm btn-dark">Clear</button>
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="table-responsive">
                    <table class="custom-table" id="logs-table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Admin</th>
                                <th>Target</th>
                                <th>Description</th>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="logs-body">
                            <!-- Table rows will be inserted here -->
                        </tbody>
                    </table>
                    
                    <!-- No data message -->
                    <div id="no-data" class="no-data" style="display: none;">
                        <i class="fas fa-clipboard-list d-block mb-3" style="font-size: 30px;"></i>
                        <p>No activity logs found matching your criteria</p>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="pagination-container">
                    <ul id="pagination" class="pagination">
                        <!-- Pagination will be inserted here -->
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Details Modal -->
    <div class="modal fade custom-modal" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Action Details</h5>
                    <span class="server-count-badge ml-2" id="serverCountBadge"></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content container that will be populated dynamically -->
                    <div id="details-content-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <script src="{{asset('plugins/notification/snackbar/snackbar.min.js')}}"></script>
        <script>
            // Configuration
            const PAGE_SIZE = 10;
            let currentPage = 1;
            let totalPages = 1;
            let totalRecords = 0;
            
            $(document).ready(function() {
                // Load filter options
                $.ajax({
                    url: "{{ route('admin.logs.filters') }}",
                    type: "GET",
                    success: function(data) {
                        // Populate admin filter
                        $.each(data.admins, function(key, admin) {
                            $('#admin_filter').append($('<option>', {
                                value: admin.id,
                                text: admin.name
                            }));
                        });
                        
                        // Populate action filter
                        $.each(data.actions, function(key, action) {
                            $('#action_filter').append($('<option>', {
                                value: action,
                                text: action.replace(/_/g, ' ').toUpperCase()
                            }));
                        });
                    }
                });
                
                // Load initial logs
                loadLogs();
                
                // Apply filters
                $('#apply_filters').on('click', function() {
                    currentPage = 1;
                    loadLogs();
                });
                
                // Clear filters
                $('#clear_filters').on('click', function() {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#admin_filter').val('');
                    $('#action_filter').val('');
                    currentPage = 1;
                    loadLogs();
                });
                
                // Add view-details click handler
                $(document).on('click', '.view-details', function(e) {
                    e.preventDefault();
                    
                    try {
                        // Get the details from the data attribute
                        let detailsAttr = $(this).attr('data-details');
                        let detailsData;
                        
                        // Replace HTML entities with their actual characters
                        detailsAttr = detailsAttr
                            .replace(/&quot;/g, '"')
                            .replace(/&#039;/g, "'")
                            .replace(/&amp;/g, '&');
                        
                        try {
                            detailsData = JSON.parse(detailsAttr);
                        } catch (e) {
                            console.error("JSON parsing error:", e);
                            detailsData = [{error: "Could not parse JSON data"}];
                        }
                        
                        // Clear previous content
                        $('#details-content-container').empty();
                        $('#serverCountBadge').empty();
                        
                        if (Array.isArray(detailsData) && detailsData.length > 0) {
                            // Display badge showing server count
                            $('#serverCountBadge').html(`<span class="badge badge-info">${detailsData.length} server${detailsData.length > 1 ? 's' : ''}</span>`);
                            
                            let content = `<div class="p-2">`;
                            
                            // Loop through each server's details
                            detailsData.forEach((serverDetail, index) => {
                                const serverName = serverDetail.server_name || `Server #${serverDetail.server_id || (index + 1)}`;
                                
                                content += `
                                <div class="server-detail mb-4 p-3" style="background: #1e2a4a; border-radius: 6px;">
                                    <h6 class="mb-3 text-white border-bottom pb-2">${serverName}</h6>
                                    ${formatDetailedServerInfo(serverDetail)}
                                </div>`;
                            });
                            
                            content += `</div>`;
                            $('#details-content-container').html(content);
                        } else {
                            // Handle single object or invalid data
                            $('#details-content-container').html(`
                                <div class="p-3">
                                    <pre style="background: #0e1726; color: #bfc9d4; padding: 15px; border-radius: 6px; max-height: 350px; overflow-y: auto;">${JSON.stringify(detailsData, null, 2)}</pre>
                                </div>
                            `);
                        }
                    } catch (error) {
                        console.error("Error showing details:", error);
                        $('#details-content-container').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Error displaying details data: ${error.message || 'Unknown error'}
                            </div>
                        `);
                    }
                    
                    // Show the modal
                    $('#detailsModal').modal('show');
                });
                
                // Pagination click handler
                $(document).on('click', '.page-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page >= 1 && page <= totalPages) {
                        currentPage = page;
                        loadLogs();
                    }
                });
            });
            
            function loadLogs() {
                // Show loading state
                $('#logs-body').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i> Loading...</td></tr>');
                
                $.ajax({
                    url: "{{ route('admin.logs.data') }}",
                    type: "GET",
                    data: {
                        page: currentPage,
                        limit: PAGE_SIZE,
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        admin_id: $('#admin_filter').val(),
                        action: $('#action_filter').val()
                    },
                    success: function(response) {
                        // Clear the table body
                        $('#logs-body').empty();
                        
                        // Check if we have any data
                        if (response.data.length > 0) {
                            // Hide no data message
                            $('#no-data').hide();
                            $('#logs-table').show();
                            
                            // Update pagination info
                            totalRecords = response.total || 0;
                            totalPages = Math.ceil(totalRecords / PAGE_SIZE);
                            
                            // Group logs by action and target
                            const groupedLogs = groupLogsByActionAndTarget(response.data);
                            
                            // Render table rows for grouped logs
                            Object.values(groupedLogs).forEach(function(logGroup) {
                                const row = createTableRowForGroup(logGroup);
                                $('#logs-body').append(row);
                            });
                            
                            // Update pagination
                            updatePagination();
                        } else {
                            // Show no data message
                            $('#logs-table').hide();
                            $('#no-data').show();
                            $('#pagination').empty();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading logs:", error);
                        $('#logs-body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
                    }
                });
            }
            
            function groupLogsByActionAndTarget(logs) {
                const grouped = {};
                
                logs.forEach(log => {
                    // Create a unique key for grouping (action + target + admin + created_at date only)
                    const date = new Date(log.created_at);
                    const dateKey = `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`;
                    const key = `${log.action}_${log.target_id || 'null'}_${log.admin_id}_${dateKey}`;
                    
                    if (!grouped[key]) {
                        grouped[key] = {
                            ...log,
                            servers: [],
                            server_count: 0,
                            multiple_details: []
                        };
                    }
                    
                    // Extract server information from details
                    if (log.details && typeof log.details === 'object' && log.details.server_id) {
                        const serverId = log.details.server_id;
                        const serverName = log.details.server_name || `Server #${serverId}`;
                        
                        if (!grouped[key].servers.some(s => s.id === serverId)) {
                            grouped[key].servers.push({
                                id: serverId,
                                name: serverName
                            });
                            
                            grouped[key].server_count += 1;
                            grouped[key].multiple_details.push(log.details);
                        }
                    } else if (log.details) {
                        grouped[key].multiple_details.push(log.details);
                    }
                });
                
                return grouped;
            }
            
            // Replace the table row creation function to generate URLs correctly
            function createTableRowForGroup(logGroup) {
                const actionType = logGroup.action.toLowerCase().replace('_', '');
                
                // Format date
                const date = new Date(logGroup.created_at);
                const formattedDate = date.toLocaleString();
                
                // Create tags
                const adminTag = `<span class="admin-tag">${logGroup.admin_name}</span>`;
                const targetTag = logGroup.target_name ? 
                    `<span class="target-tag">${logGroup.target_name}</span>` : '-';
                
                // Modify the description to include server count if applicable
                let description = logGroup.description;
                if (logGroup.server_count > 1) {
                    description += ` <span class="badge badge-secondary">on ${logGroup.server_count} servers</span>`;
                }
                
                // Store multiple_details data for modal viewing instead of linking to a separate page
                const detailsJson = JSON.stringify(logGroup.multiple_details || []).replace(/"/g, '&quot;');
                
                return `
                <tr>
                    <td>
                        <span class="status-indicator status-${actionType}"></span>
                        ${logGroup.action.replace('_', ' ').toUpperCase()}
                    </td>
                    <td>${adminTag}</td>
                    <td>${targetTag}</td>
                    <td>${description}</td>
                    <td>${formattedDate}</td>
                    <td><small>${logGroup.ip_address}</small></td>
                    <td>
                        <button class="view-details action-btn" data-details="${detailsJson}">
                            <i class="fas fa-eye mr-1"></i> View Details
                        </button>
                    </td>
                </tr>`;
            }
            
            function updatePagination() {
                $('#pagination').empty();
                
                // Don't show pagination if only one page
                if (totalPages <= 1) {
                    return;
                }
                
                // Previous button
                $('#pagination').append(`
                    <li class="${currentPage === 1 ? 'disabled' : ''}">
                        <a href="#" class="page-link" data-page="${currentPage - 1}">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                `);
                
                // Page numbers
                const maxPages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
                let endPage = Math.min(totalPages, startPage + maxPages - 1);
                
                if (endPage - startPage + 1 < maxPages) {
                    startPage = Math.max(1, endPage - maxPages + 1);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    $('#pagination').append(`
                        <li class="${i === currentPage ? 'active' : ''}">
                            <${i === currentPage ? 'span' : 'a href="#" class="page-link" data-page="' + i + '"'}>
                                ${i}
                            </${i === currentPage ? 'span' : 'a'}>
                        </li>
                    `);
                }
                
                // Next button
                $('#pagination').append(`
                    <li class="${currentPage === totalPages ? 'disabled' : ''}">
                        <a href="#" class="page-link" data-page="${currentPage + 1}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                `);
            }
            
            // Helper function to format server details in a more readable way
            function formatServerDetails(details) {
                if (!details || typeof details !== 'object') {
                    return `<pre>${JSON.stringify(details, null, 2)}</pre>`;
                }
                
                let html = '';
                
                // Group details by category
                const commonDetails = {};
                const serverDetails = {};
                const userDetails = {};
                const actionDetails = {};
                
                // Sort details into categories
                Object.entries(details).forEach(([key, value]) => {
                    if (key.includes('server')) {
                        serverDetails[key] = value;
                    } else if (key.includes('user') || key.includes('steam') || key.includes('admin') || key.includes('player')) {
                        userDetails[key] = value;
                    } else if (key.includes('reason') || key.includes('duration') || key.includes('time') || key.includes('date')) {
                        actionDetails[key] = value;
                    } else {
                        commonDetails[key] = value;
                    }
                });
                
                // Output server details first if present
                if (Object.keys(serverDetails).length > 0) {
                    html += `<div class="detail-section">
                        <span class="detail-section-title">Server Information</span>
                        ${formatDetailsGroup(serverDetails)}
                    </div>`;
                }
                
                // Output action details
                if (Object.keys(actionDetails).length > 0) {
                    html += `<div class="detail-section">
                        <span class="detail-section-title">Action Information</span>
                        ${formatDetailsGroup(actionDetails)}
                    </div>`;
                }
                
                // Output user details
                if (Object.keys(userDetails).length > 0) {
                    html += `<div class="detail-section">
                        <span class="detail-section-title">User Information</span>
                        ${formatDetailsGroup(userDetails)}
                    </div>`;
                }
                
                // Output remaining details
                if (Object.keys(commonDetails).length > 0) {
                    html += `<div class="detail-section">
                        <span class="detail-section-title">Other Information</span>
                        ${formatDetailsGroup(commonDetails)}
                    </div>`;
                }
                
                return html || `<pre>${JSON.stringify(details, null, 2)}</pre>`;
            }
            
            // Helper to format a group of details as rows
            function formatDetailsGroup(group) {
                let html = '';
                
                Object.entries(group).forEach(([key, value]) => {
                    const formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    
                    let formattedValue = value;
                    if (typeof value === 'object' && value !== null) {
                        formattedValue = `<pre>${JSON.stringify(value, null, 2)}</pre>`;
                    } else if (typeof value === 'boolean') {
                        formattedValue = value ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>';
                    }
                    
                    html += `
                        <div class="detail-row">
                            <div class="detail-label">${formattedKey}:</div>
                            <div class="detail-value">${formattedValue}</div>
                        </div>
                    `;
                });
                
                return html;
            }
            
            // Add this new helper function for detailed server information display
            function formatDetailedServerInfo(details) {
                if (!details || typeof details !== 'object') {
                    return `<pre>${JSON.stringify(details, null, 2)}</pre>`;
                }
                
                // Extract key information for the top section
                const targetInfo = {
                    name: details.target_name || details.player_name || '',
                    steamId: details.target_steam_id || details.player_steamid || details.steam_id || '',
                    ip: details.target_ip || details.player_ip || details.ip || ''
                };
                
                const actionInfo = {
                    reason: details.reason || '',
                    duration: details.duration || '',
                    ends: details.ends || '',
                    created: details.created_at || ''
                };
                
                let html = '';
                
                // Target information section
                if (targetInfo.name || targetInfo.steamId || targetInfo.ip) {
                    html += `
                    <div class="detail-section">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="detail-section-title">Target Information</span>
                            ${targetInfo.steamId ? 
                            `<a href="https://steamcommunity.com/profiles/${targetInfo.steamId}" target="_blank" class="btn btn-sm btn-dark">
                                <i class="fab fa-steam mr-1"></i> View Steam Profile
                            </a>` : ''}
                        </div>
                        
                        <div class="rounded p-3 mb-3" style="background: #131c36;">
                            ${targetInfo.name ? `<div class="detail-row">
                                <div class="detail-label">Name:</div>
                                <div class="detail-value">${targetInfo.name}</div>
                            </div>` : ''}
                            
                            ${targetInfo.steamId ? `<div class="detail-row">
                                <div class="detail-label">Steam ID:</div>
                                <div class="detail-value">${targetInfo.steamId}</div>
                            </div>` : ''}
                            
                            ${targetInfo.ip ? `<div class="detail-row">
                                <div class="detail-label">IP Address:</div>
                                <div class="detail-value highlight-info">${targetInfo.ip}</div>
                            </div>` : ''}
                        </div>
                    </div>`;
                }
                
                // Action details section
                if (actionInfo.reason || actionInfo.duration || actionInfo.ends) {
                    html += `
                    <div class="detail-section">
                        <span class="detail-section-title">Action Information</span>
                        <div class="rounded p-3 mb-3" style="background: #131c36;">
                            ${actionInfo.reason ? `<div class="detail-row">
                                <div class="detail-label">Reason:</div>
                                <div class="detail-value highlight-warning">${actionInfo.reason}</div>
                            </div>` : ''}
                            
                            ${actionInfo.duration ? `<div class="detail-row">
                                <div class="detail-label">Duration:</div>
                                <div class="detail-value">${actionInfo.duration}</div>
                            </div>` : ''}
                            
                            ${actionInfo.ends ? `<div class="detail-row">
                                <div class="detail-label">Ends:</div>
                                <div class="detail-value">${actionInfo.ends}</div>
                            </div>` : ''}
                            
                            ${actionInfo.created ? `<div class="detail-row">
                                <div class="detail-label">Created:</div>
                                <div class="detail-value">${actionInfo.created}</div>
                            </div>` : ''}
                        </div>
                    </div>`;
                }
                
                // Server information section
                const serverInfo = {};
                Object.entries(details).forEach(([key, value]) => {
                    if (key.includes('server') && key !== 'servers') {
                        serverInfo[key] = value;
                    }
                });
                
                if (Object.keys(serverInfo).length > 0) {
                    html += `
                    <div class="detail-section">
                        <span class="detail-section-title">Server Details</span>
                        <div class="rounded p-3 mb-3" style="background: #131c36;">
                            ${formatDetailsGroup(serverInfo)}
                        </div>
                    </div>`;
                }
                
                // Other details - display remaining fields
                const otherDetails = {};
                Object.entries(details).forEach(([key, value]) => {
                    if (!key.includes('server') && 
                        key !== 'target_name' && key !== 'player_name' && 
                        key !== 'target_steam_id' && key !== 'player_steamid' && key !== 'steam_id' &&
                        key !== 'target_ip' && key !== 'player_ip' && key !== 'ip' &&
                        key !== 'reason' && key !== 'duration' && key !== 'ends' && key !== 'created_at') {
                        otherDetails[key] = value;
                    }
                });
                
                if (Object.keys(otherDetails).length > 0) {
                    html += `
                    <div class="detail-section">
                        <span class="detail-section-title">Additional Information</span>
                        <div class="rounded p-3" style="background: #131c36;">
                            ${formatDetailsGroup(otherDetails)}
                        </div>
                    </div>`;
                }
                
                // Add some extra CSS for highlighting
                html += `
                <style>
                    .highlight-warning { color: #f0ad4e; }
                    .highlight-info { color: #5bc0de; }
                    .detail-section { margin-bottom: 15px; }
                </style>`;
                
                return html || `<pre>${JSON.stringify(details, null, 2)}</pre>`;
            }
        </script>
    </x-slot:footerFiles>
</x-base-layout>
