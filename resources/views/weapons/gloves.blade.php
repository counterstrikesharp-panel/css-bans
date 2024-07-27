<!-- resources/views/weapons/gloves.blade.php -->
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Gloves') }}
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
                </div>
            <!-- Modal for Glove Type Selection -->
            <div class="modal fade" id="gloveModal" tabindex="-1" aria-labelledby="gloveModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="gloveModalLabel">{{ __('gloves.select') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach($gloveTypes as $type => $gloves)
                                    <div class="col-md-3 mb-4">
                                        <button class="btn btn-light glove-select-button" data-bs-dismiss="modal" data-type="{{ $type }}">
                                            <img src="{{ $gloves[0]['image'] }}" alt="{{ ucfirst($type) }}" width="48" height="48" class="glove-tab-icon">
                                            <p>{{ ucfirst($type) }}</p>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content mt-3">
                @foreach($gloveTypes as $type => $gloves)
                    <div class="tab-pane fade" id="nav-{{ $type }}" role="tabpanel" aria-labelledby="nav-{{ $type }}-tab">
                        <div class="row lazy-load-content" data-type="{{ $type }}">
                            <!-- Gloves will be loaded here dynamically -->
                        </div>
                    </div>
                @endforeach
                <x-loader id="gloves_list_loader" />
            </div>
        </div>
            <!-- Modal for Applying Glove Skin -->
            <div class="modal fade" id="applyGloveModal" tabindex="-1" aria-labelledby="applyGloveModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="applyGloveModalLabel">{{ __('gloves.applyGloveSkin') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="selectedGloveImage" src="" alt="Glove Preview" class="img-fluid" style="max-width: 100px;"> <!-- Set max-width to control the size -->
                            <h5 id="selectedGloveName" class="mt-3"></h5>
                            <form id="applyGloveForm">
                                <input type="hidden" id="steamid" name="steamid" value="{{ Auth::user()->steam_id }}">
                                <input type="hidden" id="weapon_defindex" name="weapon_defindex">
                                <input type="hidden" id="weapon_paint_id" name="weapon_paint_id">
                                <input type="hidden" id="weapon_name" name="weapon_name">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="wearSelect">{{ __('skins.selectWear') }}</label>
                                            <select class="form-select" id="wearSelect" name="wearSelect" onchange="updateWearValue(this.value)">
                                                <option value="0.01">{{ __('skins.selectWear') }}</option>
                                                <option value="0.01">{{ __('skins.factoryNew') }}</option>
                                                <option value="0.07">{{ __('skins.minimalWear') }}</option>
                                                <option value="0.15">{{ __('skins.fieldTested') }}</option>
                                                <option value="0.38">{{ __('skins.wellWorn') }}</option>
                                                <option value="0.45">{{ __('skins.battleScarred') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="seed">{{ __('skins.seed') }}</label>
                                            <input type="text" class="form-control" id="seed" name="seed" oninput="validateSeed(this)">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="wear">{{ __('gloves.wear') }}</label>
                                    <input type="text" class="form-control" id="wear" name="wear" value="0">
                                </div>
                                <button type="button" class="btn btn-primary mt-3" id="saveGloveButton">{{ __('skins.apply') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal for Glove Preview -->
        <div class="modal fade" id="glovePreviewModal" tabindex="-1" aria-labelledby="glovePreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="glovePreviewModalLabel">{{ __('gloves.preview') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="glovePreviewImage" src="" alt="Glove Preview" class="img-fluid">
                        <h5 id="glovePreviewName" class="mt-3"></h5>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <x-slot:footerFiles>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" integrity="sha512-wl7ddkEmh7oY3G3Y6KTvnyF4ntBc2Yoi0xOqVV+tR23VCmFsXm/hATJ0vLoJqH2F0Fny9scOHUjPq0Zg82y5WQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                const glovesLoadUrl = '{!! env('VITE_SITE_DIR') !!}/weapons/loadGloves/';
            </script>
            @vite(['resources/js/skins/gloves.ts'])
            <script>
                $(document).ready(function() {
                    // Prevent the parent anchor click event for apply-glove button
                    $(document).on('click', '.apply-glove', function(event) {
                        event.preventDefault();
                        event.stopPropagation();

                        const card = $(this).closest('.card');
                        const gloveImage = card.find('img').attr('src');
                        const gloveName = card.find('b').text();
                        const weaponDefIndex = $(this).data('weapon-defindex');
                        const weaponPaintId = $(this).data('paint-id');
                        const weaponName = $(this).data('weapon-name');
                        // Set the image source and name
                        $('#selectedGloveImage').attr('src', gloveImage);
                        $('#selectedGloveName').text(gloveName);
                        $('#weapon_defindex').val(weaponDefIndex);
                        $('#weapon_paint_id').val(weaponPaintId);
                        $('#weapon_name').val(weaponName);
                        // Show the apply glove modal
                        $('#applyGloveModal').modal('show');
                    });

                    // Function to save glove
                    $('#saveGloveButton').on('click', function() {
                        const formData = new FormData(document.getElementById('applyGloveForm'));
                        fetch('{!! env('VITE_SITE_DIR') !!}/weapons/gloves/apply', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                $('.glove_active').html('');
                                $('#glove_'+$('#weapon_paint_id').val()).html('Active');
                                Snackbar.show({
                                    text: '{{ __("skins.applied") }}',
                                    actionTextColor: '#fff',
                                    backgroundColor: '#00ab55',
                                    pos: 'top-center'
                                });
                                // Close the modal
                                $('#applyGloveModal').modal('hide');
                            } else if (data.errors) {
                                // Handle validation errors
                                let errorMessages = '';
                                for (const [key, value] of Object.entries(data.errors)) {
                                    errorMessages += `${value.join(', ')}\n`;
                                }
                                alert('Validation failed:\n' + errorMessages);
                            } else {
                                alert('{{ __("gloves.error") }}');
                            }
                        }).catch(error => {
                            alert('{{ __("gloves.error") }}');
                        });
                    });

                    // Prevent default action for glove-card links to stop preview modal from opening
                    $(document).on('click', '.glove-card', function(event) {
                        event.preventDefault();
                    });

                    // Handle preview modal for glove-card links
                    $(document).on('click', '.glove-card', function(event) {
                        const skinImage = $(this).data('skin-image');
                        const skinName = $(this).data('skin-name');

                        $('#glovePreviewImage').attr('src', skinImage);
                        $('#glovePreviewName').text(skinName);

                        $('#glovePreviewModal').modal('show');
                    });
                });
                function validateSeed(input) {
                    if (!/^\d*$/.test(input.value)) {
                        input.value = input.value.replace(/[^\d]/g, '');
                    }
                }

                function updateWearValue(value) {
                    $('#wear').val(value);
                }
            </script>

        </x-slot:footerFiles>
</x-base-layout>
