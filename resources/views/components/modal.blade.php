<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">{{$title}}</h5>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Modal content goes here -->
                {{ $body }}
            </div>
            <div class="modal-footer">
                <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> {{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

