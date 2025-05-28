@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Action Details') }} - CSS-BANS
    </x-slot>
    
    <x-slot:headerFiles>
        <link rel="stylesheet" href="{{asset('plugins/notification/snackbar/snackbar.min.css')}}">
        @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])
        <style>
            .action-header {
                background-color: #1e2a4a;
                padding: 20px;
                border-radius: 6px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }
            
            .action-title {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .action-name {
                font-size: 18px;
                font-weight: 700;
                color: #e0e6ed;
            }
            
            .action-date {
                font-size: 14px;
                color: #888ea8;
            }
            
            .action-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-top: 15px;
            }
            
            .meta-item {
                display: flex;
                align-items: center;
            }
            
            .meta-label {
                font-size: 13px;
                color: #888ea8;
                margin-right: 5px;
            }
            
            .meta-value {
                font-size: 14px;
                color: #e0e6ed;
                font-weight: 600;
            }
            
            .timeline {
                position: relative;
                margin: 20px 0;
            }
            
            .timeline:before {
                content: '';
                position: absolute;
                left: 31px;
                top: 0;
                height: 100%;
                width: 2px;
                background: #3b3f5c;
            }
            
            .timeline-item {
                position: relative;
                padding-left: 70px;
                padding-bottom: 30px;
            }
            
            .timeline-item:last-child {
                padding-bottom: 0;
            }
            
            .timeline-marker {
                position: absolute;
                left: 20px;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background-color: #4361ee;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 12px;
                z-index: 1;
            }
            
            .timeline-content {
                background-color: #1a1d3a;
                padding: 15px;
                border-radius: 6px;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            }
            
            .server-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px solid #3b3f5c;
            }
            
            .server-name {
                font-size: 16px;
                font-weight: 600;
                color: #e0e6ed;
                display: flex;
                align-items: center;
            }
            
            .server-icon {
                margin-right: 10px;
                height: 24px;
                width: 24px;
                background-color: #4361ee;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }
            
            .server-badge {
                background-color: #232f4d;
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
            }
            
            .detail-section {
                background-color: #0e1726;
                padding: 15px;
                border-radius: 6px;
                margin-bottom: 15px;
            }
            
            .detail-section:last-child {
                margin-bottom: 0;
            }
            
            .section-title {
                font-size: 14px;
                font-weight: 600;
                color: #e0e6ed;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
            }
            
            .section-icon {
                margin-right: 8px;
                width: 20px;
                height: 20px;
                background-color: #1b2e4b;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
            }
            
            .detail-row {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 8px;
                font-size: 13px;
            }
            
            .detail-row:last-child {
                margin-bottom: 0;
            }
            
            .detail-label {
                width: 150px;
                color: #888ea8;
                font-weight: 500;
            }
            
            .detail-value {
                flex: 1;
                color: #e0e6ed;
                word-break: break-word;
            }
            
            .highlight-warning { color: #f0ad4e; }
            .highlight-info { color: #5bc0de; }
            .highlight-success { color: #5cb85c; }
            .highlight-danger { color: #d9534f; }
            
            .action-ban .timeline-marker { background-color: #e7515a; }
            .action-unban .timeline-marker { background-color: #8dbf42; }
            .action-mute .timeline-marker { background-color: #e2a03f; }
            .action-unmute .timeline-marker { background-color: #2196f3; }
            .action-edit .timeline-marker { background-color: #25d5e4; }
            .action-create .timeline-marker { background-color: #5c1ac3; }
            .action-delete .timeline-marker { background-color: #e7515a; }
            
            .person-link {
                color: inherit;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
            }
            
            .person-link:hover {
                text-decoration: underline;
            }
            
            .person-link i {
                margin-right: 5px;
            }
            
            .back-link {
                display: inline-flex;
                align-items: center;
                color: #bfc9d4;
                text-decoration: none;
                margin-bottom: 15px;
            }
            
            .back-link:hover {
                color: #fff;
            }
            
            .back-link i {
                margin-right: 5px;
            }
        </style>
    </x-slot>

    <div class="container">
        <a href="{{ route('admin.logs') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Admin Logs
        </a>
        
        <div class="action-header">
            <div class="action-title">
                <div class="action-name">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</div>
                <div class="action-date">{{ $log->created_at->format('F j, Y - h:i A') }}</div>
            </div>
            <div class="action-meta">
                <div class="meta-item">
                    <span class="meta-label">Admin:</span>
                    <span class="meta-value">
                        <a href="https://steamcommunity.com/profiles/{{ $log->admin_steam_id }}" target="_blank" class="person-link">
                            <i class="fab fa-steam"></i>{{ $log->admin_name }}
                        </a>
                    </span>
                </div>
                
                @if ($log->target_name)
                <div class="meta-item">
                    <span class="meta-label">Target:</span>
                    <span class="meta-value">
                        @if ($log->target_steam_id)
                        <a href="https://steamcommunity.com/profiles/{{ $log->target_steam_id }}" target="_blank" class="person-link">
                            <i class="fab fa-steam"></i>{{ $log->target_name }}
                        </a>
                        @else
                        {{ $log->target_name }}
                        @endif
                    </span>
                </div>
                @endif
                
                <div class="meta-item">
                    <span class="meta-label">Admin IP:</span>
                    <span class="meta-value highlight-info">{{ $log->ip_address }}</span>
                </div>
                
                @if (count($multipleServers) > 0)
                <div class="meta-item">
                    <span class="meta-label">Affected Servers:</span>
                    <span class="meta-value highlight-warning">{{ count($multipleServers) }}</span>
                </div>
                @endif
            </div>
        </div>
        
        @if (count($multipleServers) > 4)
        <div class="alert alert-info mb-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>This action affected {{ count($multipleServers) }} servers. All servers are listed below.</strong>
            </div>
        </div>

        <div class="mb-4">
            <details>
                <summary class="btn btn-sm btn-primary mb-2">Show Server List</summary>
                <div class="server-list-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 10px;">
                    @foreach($multipleServers as $index => $server)
                        <div class="server-item p-2 bg-dark rounded">
                            <a href="#server-{{ $server['id'] }}" class="text-white">
                                <i class="fas fa-server text-info mr-2"></i>{{ $server['name'] }}
                           