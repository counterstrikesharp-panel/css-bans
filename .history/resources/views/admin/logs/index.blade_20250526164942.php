@extends('layouts/app')
@section('title', 'Admin Activity Logs')
@section('content')
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-card-one">
            <div class="widget-heading">
                <h5>Admin Activity Logs</h5>
            </div>
            <div class="widget-content">
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

                <div class="table-responsive">
                    <table id="logs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Admin</th>
                                <th>Action</th>
                                <th>Target</th>
                                <th>Description</th>
                                <th>Details</th>
                                <th>IP Address</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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

@endsection

@push('scripts')
<script>
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
        
        // Initialize DataTable
        var table = $('#logs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.logs.data') }}",
                type: "GET",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.admin_id = $('#admin_filter').val();
                    d.action = $('#action_filter').val();
                }
            },
            columns: [
                { data: 'id' },
                { data: 'admin_name', name: 'admin_name' },
                { data: 'action' },
                { data: 'target_name', name: 'target_name' },
                { data: 'description' },
                { data: 'details' },
                { data: 'ip_address' },
                { data: 'created_at' }
            ],
            order: [[7, 'desc']], // Default order by timestamp descending
            "language": {
                "paginate": {
                    "previous": "<i class='flaticon-arrow-left-1'></i>",
                    "next": "<i class='flaticon-arrow-right'></i>"
                }
            }
        });
        
        // Apply filters
        $('#apply_filters').on('click', function() {
            table.ajax.reload();
        });
        
        // Clear filters
        $('#clear_filters').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#admin_filter').val('');
            $('#action_filter').val('');
            table.ajax.reload();
        });
        
        // View details button click handler
        $('#logs-table').on('click', '.view-details', function() {
            var details = $(this).data('details');
            $('#details-content').text(JSON.stringify(details, null, 2));
            $('#detailsModal').modal('show');
        });
    });
</script>
@endpush
