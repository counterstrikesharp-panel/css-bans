@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Activity Logs') }} - CSS-BANS
    </x-slot>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <link rel="stylesheet" href="{{asset('plugins/notification/snackbar/snackbar.min.css')}}">
        @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])
        <style>
            /* Card-based Activity Log Styles */
            .activity-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 15px;
            }
            
            .activity-card {
                background-color: #191e3a;
                border-radius: 6px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-top: 3px solid #1b2e4b;
                transition: transform 0.2s ease;
            }
            
            .activity-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            }
            
            .activity-card.ban { border-color: #e7515a; }
            .activity-card.unban { border-color: #8dbf42; }
            .activity-card.mute { border-color: #e2a03f; }
            .activity-card.unmute { border-color: #2196f3; }
            .activity-card.edit { border-color: #25d5e4; }
            .activity-card.create { border-color: #5c1ac3; }
            .activity-card.delete { border-color: #e7515a; }
            
            .card-header {
                padding: 10px 15px;
                background-color: #1e2a4a;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #3b3f5c;
            }
            
            .card-title {
                margin: 0;
                font-size: 14px;
                font-weight: 600;
                color: #e0e6ed;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }
            
            .card-date {
                font-size: 11px;
                color: #888ea8;
            }
            
            .card-body {
                padding: 12px 15px;
            }
            
            .tag {
                display: inline-block;
                font-size: 11px;
                border-radius: 3px;
                padding: 2px 6px;
                margin-right: 5px;
                margin-bottom: 5px;
                font-weight: 600;
            }
            
            .admin-tag {
                background: #3b3f5c;
                color: #fff;
            }
            
            .target-tag {
                background: #4361ee;
                color: #fff;
            }
            
            .card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 15px;
                border-top: 1px solid #3b3f5c;
                background-color: #1e2a4a;
            }
            
            .ip-text {
                color: #888ea8;
                font-size: 11px;
            }
            
            .btn-view {
                background-color: #1b2e4b;
                color: #bfc9d4;
                border: none;
                padding: 3px 8px;
                font-size: 12px;
                border-radius: 3px;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .btn-view:hover {
                background-color: #25d5e4;
                color: #1b2e4b;
            }
            
            .filter-container {
                background: #191e3a;
                padding: 15px;
                border-radius: 6px;
                margin-bottom: 20px;
                border: 1px solid #25d5e4;
            }
            
            .load-more-btn {
                background-color: #1b2e4b;
                color: #bfc9d4;
                border: none;
                padding: 10px;
                width: 100%;
                font-size: 14px;
                border-radius: 4px;
                margin-top: 15px;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .load-more-btn:hover {
                background-color: #25d5e4;
                color: #1b2e4b;
            }
            
            /* Modal Styles */
            .modal-content {
                background-color: #1b2e4b;
                color: #bfc9d4;
                border: 1px solid #3b3f5c;
            }
            
            .modal-header {
                border-bottom: 1px solid #3b3f5c;
                background-color: #1e2a4a;
            }
            
            .modal-footer {
                border-top: 1px solid #3b3f5c;
                background-color: #1e2a4a;
            }
            
            #details-content {
                background: #0e1726;
                color: #bfc9d4;
                padding: 10px;
                border-radius: 4px;
                max-height: 300px;
                overflow-y: auto;
            }
            
            .close {
                color: #bfc9d4;
                text-shadow: none;
            }
            
            .close:hover {
                color: #fff;
            }
            
            /* Empty state */
            .empty-state {
                text-align: center;
                padding: 30px 0;
                color: #888ea8;
            }
            
            .empty-state i {
                font-size: 50px;
                margin-bottom: 15px;
                opacity: 0.5;
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
                <div class="filter-container">
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
                
                <!-- Activity Cards Container -->
                <div class="activity-grid" id="logs-grid">
                    <!-- Activity cards will be inserted here -->
                </div>
                
                <!-- Empty State (will show when no logs) -->
                <div id="empty-state" class="empty-state" style="display: none;">
                    <i class="fas fa-clipboard-list"></i>
                    <p>No activity logs found</p>
                </div>
                
                <button id="load-more" class="load-more-btn">Load More</button>
            </div>
        </div>
    </section>

    <!-- Modal for Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
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
            let page = 1;
            let loading = false;
            const limit = 24;

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
                    page = 1; // Reset page count
                    $('#logs-grid').empty(); // Clear existing logs
                    loadLogs();
                });
                
                // Clear filters
                $('#clear_filters').on('click', function() {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#admin_filter').val('');
                    $('#action_filter').val('');
                    page = 1; // Reset page count
                    $('#logs-grid').empty(); // Clear existing logs
                    loadLogs();
                });
                
                // Load more button
                $('#load-more').on('click', function() {
                    loadLogs();
                });
                
                // Details button click handler
                $(document).on('click', '.view-details', function(e) {
                    e.preventDefault();
                    var details = $(this).data('details');
                    $('#details-content').text(JSON.stringify(details || {}, null, 2));
                    $('#detailsModal').modal('show');
                });
            });
            
            function loadLogs() {
                if (loading) return;
                loading = true;
                
                $('#load-more').text('Loading...').prop('disabled', true);
                
                $.ajax({
                    url: "{{ route('admin.logs.data') }}",
                    type: "GET",
                    data: {
                        page: page,
                        limit: limit,
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        admin_id: $('#admin_filter').val(),
                        action: $('#action_filter').val()
                    },
                    success: function(response) {
                        // Check if we have any logs
                        if (response.data.length > 0) {
                            // Hide empty state
                            $('#empty-state').hide();
                            
                            // Render activity cards
                            $.each(response.data, function(index, log) {
                                const activityCard = createActivityCard(log);
                                $('#logs-grid').append(activityCard);
                            });
                            
                            page++; // Increment page for next load
                            $('#load-more').text('Load More').prop('disabled', false);
                        } else {
                            // Show empty state if no logs and we're on first page
                            if (page === 1) {
                                $('#empty-state').show();
                            }
                            $('#load-more').text('No more logs to load').prop('disabled', true);
                        }
                        
                        loading = false;
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading logs:", error);
                        loading = false;
                        $('#load-more').text('Load More').prop('disabled', false);
                    }
                });
            }
            
            function createActivityCard(log) {
                const actionType = log.action.toLowerCase().replace('_', '');
                
                // Format date to be more readable
                const date = new Date(log.created_at);
                const formattedDate = date.toLocaleString();
                
                // Create action description with colorful tags
                const adminTag = `<span class="tag admin-tag">${log.admin_name}</span>`;
                const targetTag = log.target_name ? 
                    `<span class="tag target-tag">${log.target_name}</span>` : '';
                
                const card = `
                <div class="activity-card ${actionType}">
                    <div class="card-header">
                        <h6 class="card-title">${log.description}</h6>
                    </div>
                    <div class="card-body">
                        ${adminTag} ${targetTag}
                        <div class="card-date mt-2">${formattedDate}</div>
                    </div>
                    <div class="card-footer">
                        <span class="ip-text">IP: ${log.ip_address}</span>
                        <button class="view-details btn-view" data-details='${JSON.stringify(log.details || {})}'>
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                </div>`;
                
                return card;
            }
        </script>
    </x-slot:footerFiles>
</x-base-layout>
