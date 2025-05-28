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
            .timeline {
                position: relative;
                padding: 20px 0;
            }
            .timeline-item {
                position: relative;
                padding-left: 50px;
                margin-bottom: 20px;
            }
            .timeline-item:before {
                content: '';
                position: absolute;
                left: 20px;
                top: 0;
                bottom: 0;
                width: 2px;
                background-color: #ccc;
            }
            .timeline-dot {
                position: absolute;
                left: 11px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background-color: #5e72e4;
                top: 15px;
            }
            .timeline-content {
                padding: 15px;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                background-color: #fff;
                transition: all 0.3s ease;
            }
            .timeline-content:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .timeline-date {
                font-size: 0.8rem;
                color: #999;
            }
            .action-ban { background-color: #FEE2E2; }
            .action-ban .timeline-dot { background-color: #EF4444; }
            .action-unban { background-color: #D1FAE5; }
            .action-unban .timeline-dot { background-color: #10B981; }
            .action-edit { background-color: #E0E7FF; }
            .action-edit .timeline-dot { background-color: #6366F1; }
            .action-create { background-color: #FEF3C7; }
            .action-create .timeline-dot { background-color: #F59E0B; }
            .action-delete { background-color: #FDE2E2; }
            .action-delete .timeline-dot { background-color: #DC2626; }
            .action-mute { background-color: #DBEAFE; }
            .action-mute .timeline-dot { background-color: #3B82F6; }
            .action-unmute { background-color: #E0F2FE; }
            .action-unmute .timeline-dot { background-color: #0EA5E9; }
            
            .filter-section {
                margin-bottom: 30px;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 5px;
            }
            .load-more {
                display: block;
                width: 100%;
                padding: 10px;
                text-align: center;
                background-color: #f1f1f1;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 20px;
                transition: all 0.3s ease;
            }
            .load-more:hover {
                background-color: #e1e1e1;
            }
            .admin-tag {
                background-color: #5e72e4;
                color: white;
                padding: 3px 8px;
                border-radius: 12px;
                font-size: 0.8rem;
                display: inline-block;
            }
            .target-tag {
                background-color: #fd7e14;
                color: white;
                padding: 3px 8px;
                border-radius: 12px;
                font-size: 0.8rem;
                display: inline-block;
            }
            .details-button {
                background-color: #f8f9fa;
                border: 1px solid #ddd;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.8rem;
                transition: all 0.3s ease;
            }
            .details-button:hover {
                background-color: #e9ecef;
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
                <div class="filter-section">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" id="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" id="end_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Admin</label>
                                <select id="admin_filter" class="form-control">
                                    <option value="">All Admins</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Action</label>
                                <select id="action_filter" class="form-control">
                                    <option value="">All Actions</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="apply_filters" class="btn btn-primary">Apply Filters</button>
                            <button id="clear_filters" class="btn btn-secondary">Clear Filters</button>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Container -->
                <div class="timeline" id="logs-timeline">
                    <!-- Timeline items will be inserted here -->
                </div>
                
                <button id="load-more" class="load-more">Load More</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            const limit = 15;

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
                
                $('#load-more').text('Loading...');
                
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
                        } else {
                            $('#load-more').text('No more logs to load').prop('disabled', true);
                        }
                        
                        loading = false;
                        $('#load-more').text('Load More');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading logs:", error);
                        loading = false;
                        $('#load-more').text('Load More');
                    }
                });
            }
            
            function createTimelineItem(log) {
                const actionClass = 'action-' + log.action.toLowerCase().replace('_', '-');
                
                // Format date to be more readable
                const date = new Date(log.created_at);
                const formattedDate = date.toLocaleString();
                
                // Create action description with colorful tags
                const adminTag = `<span class="admin-tag">${log.admin_name}</span>`;
                const targetTag = log.target_name ? 
                    `<span class="target-tag">${log.target_name}</span>` : '';
                
                const item = `
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content ${actionClass}">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>${log.description}</h6>
                                <p>Admin: ${adminTag} ${targetTag ? 'Target: ' + targetTag : ''}</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <span class="timeline-date">${formattedDate}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <small>IP: ${log.ip_address}</small>
                                <button class="view-details details-button float-right" data-details='${JSON.stringify(log.details || {})}'>
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                return item;
            }
        </script>
    </x-slot:footerFiles>
</x-base-layout>
