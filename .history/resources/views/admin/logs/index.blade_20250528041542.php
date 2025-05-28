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
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Action Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre id="details-content" style="white-space: pre-wrap;"></pre>
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
                
                // Details button click handler
                $(document).on('click', '.view-details', function(e) {
                    e.preventDefault();
                    let detailsData = $(this).attr('data-details');
                    let parsed = detailsData;

                    // Try to parse if it's a JSON string
                    try {
                        // Remove extra escaping if present
                        if (typeof parsed === 'string') {
                            // If it's a stringified string, parse twice
                            if (parsed.startsWith('"') && parsed.endsWith('"')) {
                                parsed = JSON.parse(parsed);
                            }
                            // Try to parse JSON
                            parsed = JSON.parse(parsed);
                        }
                    } catch (err) {
                        // If parsing fails, fallback to raw string
                    }

                    // If still a string, just show as is, else pretty print
                    if (typeof parsed === 'string') {
                        $('#details-content').text(parsed);
                    } else {
                        $('#details-content').text(JSON.stringify(parsed, null, 2));
                    }
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
                            
                            // Render table rows
                            $.each(response.data, function(index, log) {
                                const row = createTableRow(log);
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
            
            function createTableRow(log) {
                const actionType = log.action.toLowerCase().replace('_', '');
                
                // Format date
                const date = new Date(log.created_at);
                const formattedDate = date.toLocaleString();
                
                // Create tags
                const adminTag = `<span class="admin-tag">${log.admin_name}</span>`;
                const targetTag = log.target_name ? 
                    `<span class="target-tag">${log.target_name}</span>` : '-';
                
                // Always encode details as JSON string, escaping quotes for HTML attribute
                const detailsJson = log.details ? JSON.stringify(log.details).replace(/'/g, "&apos;") : '{}';
                return `
                <tr>
                    <td>
                        <span class="status-indicator status-${actionType}"></span>
                        ${log.action.replace('_', ' ').toUpperCase()}
                    </td>
                    <td>${adminTag}</td>
                    <td>${targetTag}</td>
                    <td>${log.description}</td>
                    <td>${formattedDate}</td>
                    <td><small>${log.ip_address}</small></td>
                    <td>
                        <button class="view-details action-btn" data-details='${detailsJson}'>
                            <i class="fas fa-eye mr-1"></i> View
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
        </script>
    </x-slot:footerFiles>
</x-base-layout>
