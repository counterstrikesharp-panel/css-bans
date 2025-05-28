@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Action Details') }} - CSS-BANS
    </x-slot>
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <link rel="stylesheet" href="{{asset('plugins/notification/snackbar/snackbar.min.css')}}">
        @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])
        <style>
            /* Action info panel */
            .action-info-panel {
                background-color: #191e3a;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 30px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                border-left: 5px solid #4361ee;
            }
            
            .action-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid #3b3f5c;
            }
            
            .action-timestamp {
                color: #888ea8;
                font-size: 14px;
            }
            
            .action-type {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 5px;
                color: #e0e6ed;
            }
            
            .action-subtitle {
                color: #bfc9d4;
                font-size: 15px;
            }
            
            /* Timeline styles */
            .timeline-container {
                position: relative;
                margin: 30px 0;
            }
            
            .timeline {
                position: relative;
                padding-left: 30px;
                list-style: none;
                margin: 0;
            }
            
            .timeline:before {
                content: '';
                position: absolute;
                left: 9px;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #3b3f5c;
            }
            
            .timeline-item {
                position: relative;
                margin-bottom: 30px;
            }
            
            .timeline-item:last-child {
                margin-bottom: 0;
            }
            
            .timeline-item:before {
                content: '';
                position: absolute;
                left: -21px;
                top: 0;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: #4361ee;
                border: 3px solid #191e3a;
            }
            
            .timeline-item.server-ban:before { background-color: #e7515a; }
            .timeline-item.server-unban:before { background-color: #8dbf42; }
            .timeline-item.server-mute:before { background-color: #e2a03f; }
            .timeline-item.server-unmute:before { background-color: #2196f3; }
            .timeline-item.server-edit:before { background-color: #25d5e4; }
            .timeline-item.server-create:before { background-color: #5c1ac3; }
            
            .timeline-content {
                background: #1e2a4a;
                padding: 20px;
                border-radius: 6px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            }
            
            .timeline-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px solid #3b3f5c;
            }
            
            .server-name {
                font-size: 18px;
                font-weight: 600;
                color: #e0e6ed;
            }
            
            .server-id {
                background: #3b3f5c;
                color: #bfc9d4;
                padding: 3px 8px;
                border-radius: 4px;
                font-size: 12px;
            }
            
            .timeline-body {
                margin-bottom: 15px;
            }
            
            .detail-section {
                margin-bottom: 20px;
            }
            
            .detail-section:last-child {
                margin-bottom: 0;
            }
            
            .detail-section-title {
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 10px;
                color: #e0e6ed;
                border-bottom: 1px solid #3b3f5c;
                padding-bottom: 5px;
            }
            
            .detail-row {
                display: flex;
                margin-bottom: 8px;
                font-size: 13px;
            }
            
            .detail-label {
                flex: 0 0 150px;
                font-weight: 600;
                color: #888ea8;
            }
            
            .detail-value {
                flex: 1;
                color: #e0e6ed;
                word-break: break-word;
            }
            
            .highlight-warning { color: #e2a03f; }
            .highlight-info { color: #25d5e4; }
            .highlight-danger { color: #e7515a; }
            .highlight-success { color: #8dbf42; }
            
            .back-button {
                margin-bottom: 20px;
            }
            
            .tag {
                display: inline-block;
                padding: 4px 10px;
                border-radius: 30px;
                font-size: 12px;
                font-weight: 600;
                margin-right: 5px;
            }
            
            .admin-tag {
                background: #3b3f5c;
                color: #fff;
            }
            
            .target-tag {
                background: #4361ee;
                color: #fff;
            }
        </style>
    </x-slot>

    <div class="back-button">
        <a href="{{ route('admin.logs') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left mr-2"></i> Back to Logs
        </a>
    </div>

    <!-- Action info panel -->
    <div class="action-info-panel">
        <div class="action-header">
            <div>
                <h4 class="action-type">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</h4>
                <p class="action-subtitle">{{ $log->description }}</p>
                <div class="mt-3">
                    <span class="tag admin-tag">Admin: {{ $log->admin_name }}</span>
                    @if($log->target_name)
                        <span class="tag target-tag">Target: {{ $log->target_name }}</span>
                    @endif
                </div>
            </div>
            <div class="action-timestamp">
                <div class="text-right mb-2">{{ date('F j, Y', strtotime($log->created_at)) }}</div>
                <div>{{ date('h:i:s A', strtotime($log->created_at)) }}</div>
                <div class="mt-2">
                    <span class="badge badge-secondary">IP: {{ $log->ip_address }}</span>
                </div>
            </div>
        </div>
        
        <!-- Additional information -->
        <div class="row">
            <div class="col-md-6">
                <div class="detail-section">
                    <div class="detail-section-title">Admin Information</div>
                    <div class="detail-row">
                        <div class="detail-label">Admin Name</div>
                        <div class="detail-value">{{ $log->admin_name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Admin SteamID</div>
                        <div class="detail-value">
                            <a href="https://steamcommunity.com/profiles/{{ $log->admin_steam_id }}" target="_blank" class="text-info">
                                {{ $log->admin_steam_id }}
                                <i class="fas fa-external-link-alt ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @if($log->target_name)
                <div class="detail-section">
                    <div class="detail-section-title">Target Information</div>
                    <div class="detail-row">
                        <div class="detail-label">Target Name</div>
                        <div class="detail-value">{{ $log->target_name }}</div>
                    </div>
                    @if($log->target_steam_id)
                    <div class="detail-row">
                        <div class="detail-label">Target SteamID</div>
                        <div class="detail-value">
                            <a href="https://steamcommunity.com/profiles/{{ $log->target_steam_id }}" target="_blank" class="text-info">
                                {{ $log->target_steam_id }}
                                <i class="fas fa-external-link-alt ml-1"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Server Timeline -->
    <h4 class="mb-4">
        Server Activity Timeline
        @if(count($serverDetails) > 1)
            <span class="badge badge-info ml-2">{{ count($serverDetails) }} Servers</span>
        @endif
    </h4>
    
    <div class="timeline-container">
        <ul class="timeline">
            @foreach($serverDetails as $index => $serverDetail)
                @php
                    $actionType = strtolower(str_replace('_', '-', $log->action));
                    $serverName = $serverDetail->server_name ?? 'Server #' . ($serverDetail->server_id ?? $index + 1);
                    $serverId = $serverDetail->server_id ?? '';
                    $serverIp = $serverDetail->server_ip ?? '';
                @endphp
                
                <li class="timeline-item server-{{ $actionType }}">
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <div class="server-name">{{ $serverName }}</div>
                            @if($serverId)
                                <span class="server-id">ID: {{ $serverId }}</span>
                            @endif
                        </div>
                        
                        <div class="timeline-body">
                            <!-- Server Details -->
                            @if($serverIp)
                                <div class="detail-section">
                                    <div class="detail-section-title">Server Information</div>
                                    <div class="detail-row">
                                        <div class="detail-label">Server IP</div>
                                        <div class="detail-value highlight-info">{{ $serverIp }}</div>
                                    </div>
                                    
                                    @foreach($serverDetail as $key => $value)
                                        @if(strpos($key, 'server_') === 0 && $key !== 'server_name' && $key !== 'server_id' && $key !== 'server_ip')
                                            <div class="detail-row">
                                                <div class="detail-label">{{ ucwords(str_replace('_', ' ', str_replace('server_', '', $key))) }}</div>
                                                <div class="detail-value">{{ $value }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Action Details -->
                            <div class="detail-section">
                                <div class="detail-section-title">Action Details</div>
                                
                                @if(isset($serverDetail->reason))
                                    <div class="detail-row">
                                        <div class="detail-label">Reason</div>
                                        <div class="detail-value highlight-warning">{{ $serverDetail->reason }}</div>
                                    </div>
                                @endif
                                
                                @if(isset($serverDetail->duration))
                                    <div class="detail-row">
                                        <div class="detail-label">Duration</div>
                                        <div class="detail-value">{{ $serverDetail->duration }}</div>
                                    </div>
                                @endif
                                
                                @if(isset($serverDetail->ends) || isset($serverDetail->end_time))
                                    <div class="detail-row">
                                        <div class="detail-label">Ends</div>
                                        <div class="detail-value">{{ $serverDetail->ends ?? $serverDetail->end_time }}</div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Target Details for this server -->
                            @php
                                $hasTargetServerSpecificInfo = false;
                                foreach($serverDetail as $key => $value) {
                                    if(strpos($key, 'player_') === 0 || strpos($key, 'target_') === 0) {
                                        $hasTargetServerSpecificInfo = true;
                                        break;
                                    }
                                }
                            @endphp
                            
                            @if($hasTargetServerSpecificInfo)
                                <div class="detail-section">
                                    <div class="detail-section-title">Target Details</div>
                                    @foreach($serverDetail as $key => $value)
                                        @if((strpos($key, 'player_') === 0 || strpos($key, 'target_') === 0) &&
                                            $key !== 'player_name' && $key !== 'target_name' &&
                                            $key !== 'player_steamid' && $key !== 'target_steam_id')
                                            <div class="detail-row">
                                                <div class="detail-label">{{ ucwords(str_replace('_', ' ', str_replace('player_', '', str_replace('target_', '', $key)))) }}</div>
                                                <div class="detail-value">{{ $value }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Additional details that don't fit above categories -->
                            @php
                                $otherDetails = [];
                                foreach($serverDetail as $key => $value) {
                                    if(strpos($key, 'server_') !== 0 && 
                                       strpos($key, 'player_') !== 0 && 
                                       strpos($key, 'target_') !== 0 &&
                                       $key !== 'reason' && 
                                       $key !== 'duration' && 
                                       $key !== 'ends' && 
                                       $key !== 'end_time') {
                                        $otherDetails[$key] = $value;
                                    }
                                }
                            @endphp
                            
                            @if(count($otherDetails) > 0)
                                <div class="detail-section">
                                    <div class="detail-section-title">Additional Information</div>
                                    @foreach($otherDetails as $key => $value)
                                        <div class="detail-row">
                                            <div class="detail-label">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                            <div class="detail-value">
                                                @if(is_array($value) || is_object($value))
                                                    <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="back-button mt-4">
        <a href="{{ route('admin.logs') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left mr-2"></i> Back to Logs
        </a>
    </div>
    
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <script src="{{asset('plugins/notification/snackbar/snackbar.min.js')}}"></script>
    </x-slot:footerFiles>
</x-base-layout>
