<!-- resources/views/weapons/pins.blade.php -->
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Pins') }}
        </x-slot>
        <x-slot:headerFiles>
            @vite(['resources/scss/light/assets/components/tabs.scss'])
            @vite(['resources/scss/dark/assets/components/tabs.scss'])
            @vite(['resources/scss/common/common.scss'])
        </x-slot:headerFiles>
        <div class="weapon-paints">
            <div class="container mt-5">
                <div class="simple-tab">
                    <x-weapons-tab/>
                    <div class="tab-content" id="pinTabContent">
                        <div class="row mt-4">
                            @foreach($pins as $pin)
                                <div class="col-md-3 mb-4">
                                    <a class="card style-6" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#skinPreviewModal" data-skin-image="{{ $pin['image'] }}" data-skin-name="{{ $pin['name'] }}">
                                    <span  id="pin_{{$pin['id']}}" class="pin_active badge {{ ($pin['is_applied_t'] && $pin['is_applied_ct']) ? 'badge-success' : ($pin['is_applied_t'] ? 'badge-danger' : ($pin['is_applied_ct'] ? 'badge-primary' : '')) }}">
                                            {{ ($pin['is_applied_t'] && $pin['is_applied_ct']) ? __('skins.active_both') : 
                                                ($pin['is_applied_t'] ? __('skins.active_t') : 
                                                ($pin['is_applied_ct'] ? __('skins.active_ct') : '')) }}</span>
                                        <div class="loader-skins"></div> <!-- Add loader -->
                                        <img src="{{ $pin['image'] }}" class="card-img-top" alt="{{ $pin['name'] }}" crossorigin="anonymous">
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 mb-4">
                                                    <b>{{ $pin['name'] }}</b>
                                                </div>
                                                <div class="col-12 text-center">
                                                    <button class="btn btn-primary apply-pin" data-id="{{ $pin['id'] }}" data-bs-toggle="modal" data-bs-target="#applyPinModal">
                                                        <i class="fas fa-cog"></i> {{ __('Apply Pin') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for Applying Pin -->
            <div class="modal fade" id="applyPinModal" tabindex="-1" aria-labelledby="applyPinModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="applyPinModalLabel">{{ __('Apply Pin') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="selectedPinImage" src="" alt="Pin Preview" class="img-fluid" style="max-width: 100px;"> <!-- Set max-width to control the size -->
                            <h5 id="selectedPinName" class="mt-3"></h5>
                            <form id="applyPinForm">
                                <input type="hidden" id="steamid" name="steamid" value="{{Auth::user()->steam_id}}">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group">
                                    <label for="weapon_team">{{ __('Select Team') }}</label>
                                    <select class="form-select" id="weapon_team" name="weapon_team">
                                        <option value="2">{{ __('Terrorist') }}</option>
                                        <option value="3">{{ __('Counter-Terrorist') }}</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary mt-3" id="savePinButton">{{ __('Apply Pin') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Modal -->
            <div class="modal fade" id="skinPreviewModal" tabindex="-1" aria-labelledby="skinPreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="skinPreviewModalLabel">{{ __('skins.skinPreview') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img id="skinPreviewImage" src="" alt="Skin Preview">
                        <h5 id="skinPreviewName" class="text-center mt-3"></h5>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <x-slot:footerFiles>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
            @vite(['resources/js/skins/pin.ts'])
            <script>
                $(document).ready(function() {
                    // Prevent the parent anchor click event for apply-pin button
                    $(document).on('click', '.apply-pin', function(event) {
                        event.preventDefault();
                        event.stopPropagation();

                        const card = $(this).closest('.card');
                        const pinImage = card.find('img').attr('src');
                        const pinName = card.find('b').text();
                        const pinId = $(this).data('id');

                        // Set the image source and name
                        $('#selectedPinImage').attr('src', pinImage);
                        $('#selectedPinName').text(pinName);
                        $('#id').val(pinId);

                        // Show the apply pin modal
                        $('#applyPinModal').modal('show');
                    });

                    // Function to save pin
                    $('#savePinButton').on('click', function() {
                        const formData = new FormData(document.getElementById('applyPinForm'));
                        let route;
                        route = '{!! env('VITE_SITE_DIR') !!}/weapons/pins/apply';
                        fetch(route, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                Snackbar.show({
                                    text: '{{ __("skins.applied") }}',
                                    actionTextColor: '#fff',
                                    backgroundColor: '#00ab55',
                                    pos: 'top-center'
                                });
                                $('.pin_active').html('');
                                $("#pin_"+$('#id').val()).html('Active');
                                // Close the modal
                                $('#applyPinModal').modal('hide');
                            } else if (data.errors) {
                                // Handle validation errors
                                let errorMessages = '';
                                for (const [key, value] of Object.entries(data.errors)) {
                                    errorMessages += `${value.join(', ')}\n`;
                                }
                                alert('Validation failed:\n' + errorMessages);
                            } else {
                                alert('{{ __("pin.error") }}');
                            }
                        }).catch(error => {
                            alert('{{ __("pin.error") }}');
                        });
                    });

                    // Prevent default action for pin-card links to stop preview modal from opening
                    $(document).on('click', '.pin-card', function(event) {
                        event.preventDefault();
                    });

                    // Handle preview modal for pin-card links
                    $(document).on('click', '.pin-card', function(event) {
                        const skinImage = $(this).data('skin-image');
                        const skinName = $(this).data('skin-name');

                        $('#skinPreviewImage').attr('src', skinImage);
                        $('#skinPreviewName').text(skinName);

                        $('#skinPreviewModal').modal('show');
                    });
                });
            </script>

            </x-slot>
</x-base-layout>
