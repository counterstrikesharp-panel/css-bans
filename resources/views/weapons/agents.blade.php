<!-- resources/views/weapons/agents.blade.php -->
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Agents') }}
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
                    <div class="tab-content" id="agentTabContent">
                        <div class="row mt-4">
                            @foreach($agents as $agent)
                                <div class="col-md-3 mb-4">
                                    <div class="card style-6">
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#skinPreviewModal" data-skin-image="{{ $agent['image'] }}" data-skin-name="{{ $agent['agent_name'] }}">
                                            @if($agent['team'] == 3)
                                                <span class="badge badge-primary">CT</span>
                                            @else
                                                <span class="badge badge-warning">T</span>
                                            @endif

                                            <span id="agent_{{$agent['model']}}" class="badge badge-{{ $agent['team'] == 3 ? 'primary' : 'warning' }}">{{ $agent['is_applied'] ? __('skins.active') : '' }}</span>
                                            <div class="loader-skins"></div> <!-- Add loader -->
                                            <img src="{{ $agent['image'] }}" class="card-img-top" alt="{{ $agent['agent_name'] }}" crossorigin="anonymous">
                                        </a>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 mb-4">
                                                    <b>{{ $agent['agent_name'] }}</b>
                                                </div>
                                                <div class="col-12 text-center">
                                                    <button class="btn btn-primary apply-agent" data-agent-name="{{ $agent['model'] }}" data-team="{{ $agent['team'] }}">
                                                        <i class="fas fa-cog"></i> {{ __('agent.applyAgent') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Applying Agent Skin -->
            <div class="modal fade" id="applyAgentModal" tabindex="-1" aria-labelledby="applyAgentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="applyAgentModalLabel">{{ __('agent.applyAgentSkin') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="selectedAgentImage" src="" alt="Agent Preview" class="img-fluid" style="max-width: 100px;"> <!-- Set max-width to control the size -->
                            <h5 id="selectedAgentName" class="mt-3"></h5>
                            <form id="applyAgentForm">
                                <input type="hidden" id="steamid" name="steamid" value="{{Auth::user()->steam_id}}">
                                <input type="hidden" id="team" name="team">
                                <input type="hidden" id="agent_name" name="agent_name">
                                <button type="button" class="btn btn-primary mt-3" id="saveAgentButton">{{ __('skins.apply') }}</button>
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
            <script>
                $(document).ready(function() {
                    // Function to open apply agent modal
                    $(document).on('click', '.apply-agent', function(event) {
                        // Prevent the default action and event from bubbling up to the anchor tag
                        event.preventDefault();
                        event.stopPropagation();

                        const card = $(this).closest('.card');
                        const agentImage = card.find('img').attr('src');
                        const agentName = card.find('b').text();
                        const team = $(this).data('team');
                        const agentNameData = $(this).data('agent-name');

                        // Set the image source and name
                        $('#selectedAgentImage').attr('src', agentImage);
                        $('#selectedAgentName').text(agentName);
                        $('#team').val(team);
                        $('#agent_name').val(agentNameData);

                        $('#applyAgentModal').modal('show');
                    });

                    // Function to save agent
                    $('#saveAgentButton').on('click', function() {
                        const formData = new FormData(document.getElementById('applyAgentForm'));
                        let route;
                        route = '{!! env('VITE_SITE_DIR') !!}/weapons/agents/apply';
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
                                $("#agent_" + $('#agent_name').val().replace(/\//g, '\\/')).html('Active');                                $('agent_active').html('');
                                // Close the modal
                                $('#applyAgentModal').modal('hide');
                            } else if (data.errors) {
                                // Handle validation errors
                                let errorMessages = '';
                                for (const [key, value] of Object.entries(data.errors)) {
                                    errorMessages += `${value.join(', ')}\n`;
                                }
                                alert('Validation failed:\n' + errorMessages);
                            } else {
                                alert('{{ __("agent.error") }}');
                            }
                        }).catch(error => {
                            alert(error);
                        });
                    });
                });
            </script>
            @vite(['resources/js/skins/agents.ts'])
            </x-slot>
</x-base-layout>
