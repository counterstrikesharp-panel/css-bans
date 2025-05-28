@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Activity Logs') }} - CSS-BANS
    </x-slot>
    @vite(['resources/scss/dark/assets/components/datatable.scss'])
    <x-slot:headerFiles>
        <link rel="stylesheet" href="{{asset('plugins/notification/snackbar/snackbar.min.css')}}">
        @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])
        <!-- Timeline custom CSS -->
        <style>
            .timeline {
                position: relative;
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
            }
            .timeline::before {
                content: '';
                position: absolute;
                width: 2px;
                background-color: #1b55e2;
                top: 0;
                bottom: 0;
                left: 50%;
                margin-left: -1px;
            }
            .timeline-item {
                padding: 10px 40px;
                position: relative;
                background-color: inherit;
                width: 50%;
            }
            .timeline-item.left {
                left: 0;
            }
            .timeline-item.right {
                left: 50%;
            }
            .timeline-item::before {
                content: '';
                position: absolute;
                width: 25px;
                height: 25px;
                right: -12.5px;
                background-color: #1b55e2;
                border: 4px solid #dee2e6;
                top: 15px;
                border-radius: 50%;
                z-index: 1;
            }
            .timeline-item.left::before {
                right: -12.5px;
            }
            .timeline-item.right::before {
                left: -12.5px;
            }
            .timeline-content {
                padding: 15px;
                background-color: white;
                position: relative;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            .timeline-item.right .timeline-content {
                border-left: 3px solid #1b55e2;
            }
            .timeline-item.left .timeline-content {
                border-right: 3px solid #1b55e2;
            }
            .timeline-date {
                font-weight: bold;
                color: #1b55e2;
                margin-bottom: 5px;
            }
            .timeline-action {
                font-weight: bold;
                margin-bottom: 5px;
            }
            .timeline-action.ban { color: #e7515a; }
            .timeline-action.unban { color: #8dbf42; }
            .timeline-action.mute { color: #e2a03f; }
            .timeline-action.unmute { color: #2196f3; }
            .timeline-action.admin_add { color: #5c1ac3; }
            .timeline-action.admin_edit { color: #25d5e4; }
            .timeline-action.admin_delete { color: #e7515a; }
            
            .date-header {
                text-align: center;
                margin: 20px 0;
                padding: 8px;
                background-color: #1b55e2;
                color: white;
                border-radius: 4px;
                position: relative;
                z-index: 2;
            }
            
            .timeline-target {
                margin-top: 5px;
                font-style: italic;
            }
            
            @media screen and (max-width: 768px) {
                .timeline::before {
                    left: 30px;
                }
                .timeline-item {
                    width: 100%;
                    padding-left: 70px;
                    padding-right: 20px;
                }
                .timeline-item.right,
                .timeline-item.left {
                    left: 0;
                }
                .timeline-item.left::before,
                .timeline-item.right::before {
                    left: 18px;
                }
            }
        </style>
    </x-slot>

    @if (session('success'))
        <x-alert type="success" :message="session('success')"/>
    @endif
    
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>{{ __('Admin Activity Logs') }}</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Admin</label>
                                <select id="admin_filter" class="form-control">
                                    <option value="">All Admins</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Action Type</label>
                                <select id="action_filter" class="form-control">
                                    <option value="">All Actions</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group">
                                <button id="apply_filters" class="btn btn-primary">Apply Filters</button>
                                <button id="clear_filters" class="btn btn-secondary ml-2">Reset</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-container">
                        <div class="timeline-stats mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6>Total Logs</h6>
                                            <h3 id="total-logs-count">0</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center">
                                            <h6>Ban Actions</h6>
                                            <h3 id="ban-count">0</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h6>Unban Actions</h6>
                                            <h3 id="unban-count">0</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h6>Other Actions</h6>
                                            <h3 id="other-actions-count">0</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="timeline" class="timeline">
                            <!-- Timeline content will be loaded here -->
                            <div class="text-center p-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Loading activity logs...</p>
                            </div>
                        </div>
                        
                        <div id="no-logs-message" class="text-center p-5" style="display: none;">
                            <div class="alert alert-info">
                                <h5>No activity logs found</h5>
                                <p>There are no logs matching your current filters.</p>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button id="load-more" class="btn btn-outline-primary" style="display: none;">Load More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Action Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="detail-admin">
                        <strong>Admin:</strong> <span id="modal-admin-name"></span>
                        <a href="#" id="modal-admin-profile" target="_blank" class="ml-2"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                    <div id="detail-target" class="mt-2">
                        <strong>Target:</strong> <span id="modal-target-name"></span>
                        <a href="#" id="modal-target-profile" target="_blank" class="ml-2"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                    <div id="detail-action" class="mt-2">
                        <strong>Action:</strong> <span id="modal-action"></span>
                    </div>
                    <div id="detail-description" class="mt-2">
                        <strong>Description:</strong> <span id="modal-description"></span>
                    </div>
                    <div id="detail-ip" class="mt-2">
                        <strong>IP Address:</strong> <span id="modal-ip"></span>
                    </div>
                    <div id="detail-time" class="mt-2">
                        <strong>Time:</strong> <span id="modal-time"></span>
                    </div>
                    <div id="detail-json" class="mt-3">
                        <strong>Details:</strong>
                        <pre id="details-content" class="mt-2 p-2 bg-light" style="white-space: pre-wrap;"></pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <x-slot:footerFiles>
        <script src="{{asset('plugins/notification/snackbar/snackbar.min.js')}}"></script>
        <script>
            $(document).ready(function() {
                let page = 1;
                let hasMorePages = false;
                let isLoading = false;
                let selectedAdminId = '';
                let selectedAction = '';
                
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
                        
                        // Load initial data
                        loadLogs();
                    }
                });
                
                // Apply filters
                $('#apply_filters').on('click', function() {
                    page = 1;
                    selectedAdminId = $('#admin_filter').val();
                    selectedAction = $('#action_filter').val();
                    loadLogs(true);
                });
                
                // Clear filters
                $('#clear_filters').on('click', function() {
                    $('#admin_filter').val('');
                    $('#action_filter').val('');
                    page = 1;
                    selectedAdminId = '';
                    selectedAction = '';
                    loadLogs(true);
                });
                
                // Load more button
                $('#load-more').on('click', function() {
                    if (!isLoading && hasMorePages) {
                        page++;
                        loadLogs(false);
                    }
                });
                
                // Details modal handler
                $(document).on('click', '.log-details-btn', function() {
                    const logId = $(this).data('id');
                    
                    $.ajax({
                        url: "{{ route('admin.logs.get') }}/" + logId,
                        type: "GET",
                        success: function(data) {
                            $('#modal-admin-name').text(data.admin_name);
                            $('#modal-admin-profile').attr('href', 'https://steamcommunity.com/profiles/' + data.admin_steam_id);
                            
                            $('#modal-target-name').text(data.target_name || 'N/A');
                            if (data.target_steam_id) {
                                $('#modal-target-profile').attr('href', 'https://steamcommunity.com/profiles/' + data.target_steam_id).show();
                            } else {
                                $('#modal-target-profile').hide();
                            }
                            
                            $('#modal-action').text(data.action.replace(/_/g, ' ').toUpperCase());
                            $('#modal-description').text(data.description);
                            $('#modal-ip').text(data.ip_address);
                            $('#modal-time').text(data.created_at);
                            
                            if (data.details) {
                                $('#details-content').text(JSON.stringify(data.details, null, 2)).parent().show();
                            } else {
                                $('#detail-json').hide();
                            }
                            
                            $('#detailsModal').modal('show');
                        }
                    });
                });
                
                // Load logs function
                function loadLogs(reset = false) {
                    if (isLoading) return;
                    
                    isLoading = true;
                    
                    if (reset) {
                        $('#timeline').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2">Loading activity logs...</p></div>');
                        $('#load-more').hide();
                        $('#no-logs-message').hide();
                    } else {
                        $('#load-more').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').prop('disabled', true);
                    }
                    