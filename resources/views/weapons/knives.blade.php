<!-- resources/views/weapons/knives.blade.php -->
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Knife Skins') }}
        </x-slot>
        <x-slot:headerFiles>
            @vite(['resources/scss/light/assets/components/tabs.scss'])
            @vite(['resources/scss/dark/assets/components/tabs.scss'])
            @vite(['resources/scss/common/common.scss'])
        </x-slot:headerFiles>

        @auth
            <div class="weapon-paints">
                <div class="container mt-5">
                    <div class="simple-tab">
                        <x-weapons-tab/>
                    </div>
                </div>

                <!-- Modal for Knife Category Selection -->
                <div class="modal fade" id="knifeModal" tabindex="-1" aria-labelledby="knifeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="knifeModalLabel">{{ __('Select Knife Category') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    @foreach($knifeCategories as $category => $knives)
                                        <div class="col-md-3 mb-4">
                                            <button class="btn btn-light knife-select-button" data-bs-dismiss="modal" data-category="{{ $category }}">
                                                <img src="{{ $knives[0]['image'] }}" alt="{{ ucfirst(explode("|",$knives[0]['paint_name'])[0]) }}" width="48" height="48" class="knife-tab-icon">
                                                <p>{{ ucfirst(explode("|",$knives[0]['paint_name'])[0]) }}</p>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> {{ __('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="knifeTabContent">
                    @foreach($knifeCategories as $category => $knives)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="knife-{{ $category }}-pane" role="tabpanel" aria-labelledby="knife-{{ $category }}-tab">
                            <!-- Add the content for each tab here -->
                            <div class="row mt-4 lazy-content" data-type="{{ $category }}">
                                <!-- Content will be loaded dynamically via AJAX -->
                            </div>
                        </div>
                    @endforeach
                    <x-loader id="knife_list_loader" />
                </div>

                <!-- Modal for Applying Skin -->
                <div class="modal fade" id="applySkinModal" tabindex="-1" aria-labelledby="applySkinModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="applySkinModalLabel">{{ __('Apply Knife Skin') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="selectedSkinImage" src="" alt="Skin Preview" class="img-fluid" style="max-width: 100px;">
                                <h5 id="selectedSkinName" class="mt-3"></h5>
                                <form id="applySkinForm">
                                    <input type="hidden" id="steamid" name="steamid" value="{{ Auth::user()->steam_id }}">
                                    <input type="hidden" id="weapon_category" name="weapon_category">
                                    <input type="hidden" id="weapon_defindex" name="weapon_defindex">
                                    <input type="hidden" id="weapon_paint_id" name="weapon_paint_id">
                                    <input type="hidden" id="weapon_name" name="weapon_name">
                                    <div id="knife-options">
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
                                    </div>
                                    <button type="button" class="btn btn-primary mt-3" id="saveSkinButton">{{ __('skins.apply') }}</button>
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
                                <h5 class="modal-title" id="skinPreviewModalLabel">{{ __('Skin Preview') }}</h5>
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
                <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" integrity="sha512-q583ppKrCRc7N5O0n2nzUiJ+suUv7Et1JGels4bXOaMFQcamPk9HjdUknZuuFjBNs7tsMuadge5k9RzdmO+1GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <script>
                    const knivesLoadUrl = '{!! env('VITE_SITE_DIR') !!}/weapons/loadKnives/';
                </script>
                <script>
                    $(document).ready(function() {
                        // Prevent the parent anchor click event for apply-skin button
                        $(document).on('click', '.apply-skin', function(event) {
                            event.preventDefault();
                            event.stopPropagation();

                            const card = $(this).closest('.card');
                            const skinImage = card.find('img').attr('src');
                            const skinName = card.find('b').text();
                            const weaponCategory = $(this).data('weapon-category');
                            const weaponDefIndex = $(this).data('weapon-defindex');
                            const weaponPaintId = $(this).data('weapon-paint-id');
                            const weaponName = $(this).data('weapon-name');

                            // Set the image source and name
                            $('#selectedSkinImage').attr('src', skinImage);
                            $('#selectedSkinName').text(skinName);
                            $('#weapon_category').val(weaponCategory);
                            $('#weapon_defindex').val(weaponDefIndex);
                            $('#weapon_paint_id').val(weaponPaintId);
                            $('#weapon_name').val(weaponName);

                            // Show the apply skin modal
                            $('#applySkinModal').modal('show');
                        });

                        // Function to save skin
                        $('#saveSkinButton').on('click', function() {
                            const formData = new FormData(document.getElementById('applySkinForm'));
                            const weaponCategory = $('#weapon_category').val();
                            let route;
                            route = '{!! env('VITE_SITE_DIR') !!}/weapons/knives/apply';

                            fetch(route, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            }).then(response => response.json()).then(data => {
                                if (data.success) {
                                    $(".skin_active").html('');
                                    $("#skin_"+$('#weapon_paint_id').val()+"_"+$('#weapon_defindex').val()).html('active');
                                    Snackbar.show({
                                        text: '{{ __("skins.applied") }}',
                                        actionTextColor: '#fff',
                                        backgroundColor: '#00ab55',
                                        pos: 'top-center'
                                    });
                                    // Close the modal
                                    $('#applySkinModal').modal('hide');
                                } else if (data.errors) {
                                    // Handle validation errors
                                    let errorMessages = '';
                                    for (const [key, value] of Object.entries(data.errors)) {
                                        errorMessages += `${value.join(', ')}\n`;
                                    }
                                    alert('Validation failed:\n' + errorMessages);
                                } else {
                                    alert('{{ __("skins.error") }}');
                                }
                            }).catch(error => {
                                alert('{{ __("skins.error") }}');
                            });
                        });

                        // Prevent default action for weapon-card links to stop preview modal from opening
                        $(document).on('click', '.weapon-card', function(event) {
                            event.preventDefault();
                        });

                        // Handle preview modal for weapon-card links
                        $(document).on('click', '.weapon-card', function(event) {
                            const skinImage = $(this).data('skin-image');
                            const skinName = $(this).data('skin-name');

                            $('#skinPreviewImage').attr('src', skinImage);
                            $('#skinPreviewName').text(skinName);

                            $('#skinPreviewModal').modal('show');
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

                    function targetTabAction(targetTab) {
                        const currentActiveTab = document.querySelector('.tab-pane.show.active');
                        currentActiveTab.classList.remove('show', 'active');
                        targetTab.classList.add('show', 'active');

                        // Update active state of buttons
                        const currentActiveButton = document.querySelector('.nav-link.active');
                        if (currentActiveButton) {
                            currentActiveButton.classList.remove('active');
                        }
                        document.getElementById('weapon-dropdown-tab').classList.add('active');
                    }
                </script>

                @vite(['resources/js/skins/knives.ts'])
                </x-slot>
                @else
                    <!-- Login with Steam modal -->
                    <div class="container">
                        <div id="loginAlert" class="alert alert-gradient  fade show" role="alert" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050;">
                            <strong>{{ __('skins.loginRequired') }}</strong> {{ __('skins.needToLogin') }}
                            <a href="{{ getAppSubDirectoryPath().'/auth/steam' }}" class="btn btn-success">
                                <i class="fab fa-steam"></i> {{ __('skins.loginWithSteam') }}
                            </a>
                        </div>
                    </div>

                    <x-slot:footerFiles>
                        </x-slot>
    @endauth
</x-base-layout>
