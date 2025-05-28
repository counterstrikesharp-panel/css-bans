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
            .compact-timeline {
                position: relative;
                margin: 0;
                padding: 0;
            }
            
            .compact-timeline-item {
                display: flex;
                position: relative;
                border-left: 2px solid #1e2a78;
                margin-bottom: 0;
                padding: 10px 0 10px 20px;
            }
            
            .compact-timeline-item:last-child {
                border-left-color: transparent;
            }
            
            .compact-timeline-item::before {
                content: '';
                position: absolute;
                left: -8px;
                top: 10px;
                width: 14px;
                height: 14px;
                border-radius: 50%;
                background: #1e2a78;
                border: 2px solid #000;
            }
            
            .compact-timeline-content {
                width: 100%;
                background: #191e3a;
                border-radius: 4px;
                padding: 12px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.2);
                margin-left: 15px;
            }
            
            .compact-timeline-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
            }
            
            .compact-timeline-title {
                font-size: 14px;
                font-weight: 600;
                margin: 0;
                color: #e0e6ed;
            }
            
            .compact-timeline-date {
                font-size: 12px;
                color: #888ea8;
                align-self: flex-start;
            }
            
            .compact-timeline-body {
                font-size: 13px;
                color: #bfc9d4;
            }
            
            .tag {
                display: inline-block;
                font-size: 11px;
                border-radius: 3px;
                padding: 2px 6px;
                margin-right: 5px;
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
            
            .compact-timeline-item.ban::before { background-color: #e7515a; }
            .compact-timeline-item.unban::before { background-color: #8dbf42; }
            .compact-timeline-item.mute::before { background-color: #e2a03f; }
            .compact-timeline-item.unmute::before { background-color: #2196f3; }
            .compact-timeline-item.edit::before { background-color: #25d5e4; }
            .compact-timeline-item.create::before { background-color: #5c1ac3; }
            .compact-timeline-item.delete::before { background-color: #e7515a; }

            .filter-container {
                background: #191e3a;
                padding: 15px;
                border-radius: 4px;
                margin-bottom: 20px;
                border: 1px solid #25d5e4;
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
            
            .ip-text {
                color: #888ea8;
                font-size: 11px;
            }

            #detailsModal .modal-content {
                background-color: #1b2e4b;
                color: #bfc9d4;
            }
            
            #detailsModal .modal-header {
                border-bottom: 1px solid #3b3f5c;
            }
            
            #detailsModal .modal-footer {
                border-top: 1px solid #3b3f5c;
            }
            
            #details-content {
                background: #0e1726;
                color: #bfc9d4;
                padding: 10px;
                border-radius: 4px;
                max-height: 300px;
                overflow-y: auto;
            }
            
            .compact-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 10px;
                border-top: 1px solid #3b3f5c;
                padding-top: 8px;
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
                <!-- Compact Filter Section -->
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
                
                <!-- Compact Timeline Container -->
                <div class="compact-timeline" id="logs-timeline">
                    <!-- Timeline items will be inserted here -->
                </div>
                
                <button id="load-more" class="load-more-btn">Load More</button>
            </div>
        </div>
    </section>

    <!-- Modal for Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
            const limit = 20;

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
                    $('#logs-timeline').empty(); // Clear existing logs
                    loadLogs();
                });
                
                // Clear filters
                $('#clear_filters').on('click', function() {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#admin_filter').val('');
                    $('#action_filter').val('');
                    page = 1; // Reset page count
                    $('#logs-timeline').empty(); // Clear existing logs
                    loadLogs();
                });
                
                // Load more button
                $('#load-more').on('click', function() {
                    loadLogs();
                });
                
                // Details button click handler
                $(document).on('click', '.view-details', function() {
                    var details = $(this).data('details');
                    $('#details-content').text(JSON.stringify(details, null, 2));
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
                        // Render timeline items
                        if (response.data.length > 0) {
                            $.each(response.data, function(index, log) {
                                const timelineItem = createTimelineItem(log);
                                $('#logs-timeline').append(timelineItem);
                            });
                            
                            page++; // Increment page for next load
                            $('#load-more').text('Load More').prop('disabled', false);
                        } else {
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
            
            function createTimelineItem(log) {
                const actionType = log.action.toLowerCase().replace('_', '');
                
                // Format date to be more readable
                const date = new Date(log.created_at);
                const formattedDate = date.toLocaleString();
                
                // Create action description with colorful tags
                const adminTag = `<span class="tag admin-tag">${log.admin_name}</span>`;
                const targetTag = log.target_name ? 
                    `<span class="tag target-tag">${log.target_name}</span>` : '';
                
                const item = `
                <div class="compact-timeline-item ${actionType}">
                    <div class="compact-timeline-content">
                        <div class="compact-timeline-header">
                            <h6 class="compact-timeline-title">${log.description}</h6>
                            <span class="compact-timeline-date">${formattedDate}</span>
                        </div>
                        <div class="compact-timeline-body">
                            ${adminTag} ${targetTag}
                        </div>
                        <div class="compact-footer">
                            <span class="ip-text">IP: ${log.ip_address}</span>
                            <button class="view-details btn-view" data-details='${JSON.stringify(log.details || {})}'>
                                <i class="fas fa-info-circle"></i> Details
                            </button>
                        </div>
                    </div>
                </div>`;
                
                return item;
            }
        </script>
    </x-slot:footerFiles>
</x-base-layout>
