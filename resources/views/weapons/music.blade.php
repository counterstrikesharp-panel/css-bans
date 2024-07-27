<!-- resources/views/weapons/music.blade.php -->
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Music') }}
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
                    <div class="tab-content" id="musicTabContent">
                        <div class="row mt-4">
                            @foreach($music as $track)
                                <div class="col-md-3 mb-4">
                                    <a class="card style-6" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#skinPreviewModal" data-skin-image="{{ $track['image'] }}" data-skin-name="{{ $track['name'] }}">
                                        <span  id="music_{{$track['id']}}" class="music_active badge badge-danger">{{ $track['is_applied'] ? __('skins.active') : '' }}</span>
                                        <div class="loader-skins"></div> <!-- Add loader -->
                                        <img src="{{ $track['image'] }}" class="card-img-top" alt="{{ $track['name'] }}" crossorigin="anonymous">
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 mb-4">
                                                    <b>{{ $track['name'] }}</b>
                                                </div>
                                                <div class="col-12 text-center">
                                                    <button class="btn btn-primary apply-music" data-music-id="{{ $track['id'] }}" data-bs-toggle="modal" data-bs-target="#applyMusicModal">
                                                        <i class="fas fa-cog"></i> {{ __('music.applyMusic') }}
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
            <!-- Modal for Applying Music Skin -->
            <div class="modal fade" id="applyMusicModal" tabindex="-1" aria-labelledby="applyMusicModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="applyMusicModalLabel">{{ __('music.applyMusicSkin') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="selectedMusicImage" src="" alt="Music Preview" class="img-fluid" style="max-width: 100px;"> <!-- Set max-width to control the size -->
                            <h5 id="selectedMusicName" class="mt-3"></h5>
                            <form id="applyMusicForm">
                                <input type="hidden" id="steamid" name="steamid" value="{{Auth::user()->steam_id}}">
                                <input type="hidden" id="music_id" name="music_id">
                                <button type="button" class="btn btn-primary mt-3" id="saveMusicButton">{{ __('skins.apply') }}</button>
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
            @vite(['resources/js/skins/music.ts'])
            <script>
                $(document).ready(function() {
                    // Prevent the parent anchor click event for apply-music button
                    $(document).on('click', '.apply-music', function(event) {
                        event.preventDefault();
                        event.stopPropagation();

                        const card = $(this).closest('.card');
                        const musicImage = card.find('img').attr('src');
                        const musicName = card.find('b').text();
                        const musicId = $(this).data('music-id');

                        // Set the image source and name
                        $('#selectedMusicImage').attr('src', musicImage);
                        $('#selectedMusicName').text(musicName);
                        $('#music_id').val(musicId);

                        // Show the apply music modal
                        $('#applyMusicModal').modal('show');
                    });

                    // Function to save music
                    $('#saveMusicButton').on('click', function() {
                        const formData = new FormData(document.getElementById('applyMusicForm'));
                        let route;
                        route = '{!! env('VITE_SITE_DIR') !!}/weapons/music/apply';
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
                                $('.music_active').html('');
                                $("#music_"+$('#music_id').val()).html('Active');
                                // Close the modal
                                $('#applyMusicModal').modal('hide');
                            } else if (data.errors) {
                                // Handle validation errors
                                let errorMessages = '';
                                for (const [key, value] of Object.entries(data.errors)) {
                                    errorMessages += `${value.join(', ')}\n`;
                                }
                                alert('Validation failed:\n' + errorMessages);
                            } else {
                                alert('{{ __("music.error") }}');
                            }
                        }).catch(error => {
                            alert('{{ __("music.error") }}');
                        });
                    });

                    // Prevent default action for music-card links to stop preview modal from opening
                    $(document).on('click', '.music-card', function(event) {
                        event.preventDefault();
                    });

                    // Handle preview modal for music-card links
                    $(document).on('click', '.music-card', function(event) {
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
