@php use App\Helpers\CommonHelper;use App\Helpers\PermissionsHelper; @endphp
@php
    $onlyManageAdminPerms = [
       PermissionsHelper::isSuperAdmin(),
       PermissionsHelper::hasAdminCreatePermission(),
       PermissionsHelper::hasAdminEditPermission(),
       PermissionsHelper::hasAdminDeletePermission(),
   ];
@endphp
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{getAppSubDirectoryPath();}}/">
                        <img src="{{url(getAppSubDirectoryPath().'/logo/logo-dark.svg')}}" class="navbar-logo logo-dark"
                             alt="logo">
                        <img src="{{url(getAppSubDirectoryPath().'/logo/logo-light.svg')}}"
                             class="navbar-logo logo-light" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{getAppSubDirectoryPath();}}/" class="nav-link"> {{env('LOGO_NAME')}} </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        @if (!Request::is('collapsible-menu/*'))
            <div class="profile-info">
                <div class="user-info">
                    <div class="profile-img">
                        <img src="{{ Auth::user()?->avatar ?: Vite::asset('resources/images/profile-30.png') }}"
                             alt="avatar">
                    </div>
                    <div class="profile-content">
                        <h6 class="">{{Auth::user()?->name ? :  __('admins.guest') }}</h6>
                        <p>
                            @if(!PermissionsHelper::isSuperAdmin())
                                @if(PermissionsHelper::hasMutePermission())
                                    <i class="fas fa-microphone-alt-slash fa-fw me-3"></i>
                                @endif
                                @if(PermissionsHelper::hasMutePermission())
                                    <i class="fas fa-ban fa-fw me-3"></i>
                                @endif
                            @else
                                {{ __('admins.panelOwner') }}
                            @endif
                        </p>
                        @if(!empty(Auth::user()))
                            {!! session('rank_image') !!}
                            {!! session('rating_image') !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories">
            <li class="menu {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">
                <a href="{{getAppSubDirectoryPath();}}/" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>{{ __('dashboard.title') }}</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ Request::is('*list/bans*') || Request::is('*list/mutes*') || Request::is('*appeals*') || Request::is('*reports*') ? 'active' : '' }}">
                <a href="#serverSection" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*list/bans*') || Request::is('*list/mutes*') || Request::is('*appeals*') || Request::is('*reports*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-server">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6.01" y2="6"></line>
                            <line x1="6" y1="18" x2="6.01" y2="18"></line>
                        </svg>
                        <span>{{ __('dashboard.server') }}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ Request::is('*list/bans*') || Request::is('*list/mutes*') || Request::is('*appeals*') || Request::is('*reports*') ? 'show' : '' }}" id="serverSection" data-bs-parent="#accordionExample">
                    <li class="{{ Request::is('*list/bans*') ? 'active' : '' }}">
                        <a href="{{getAppSubDirectoryPath();}}/list/bans" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-ban fa-fw me-3"></i>
                                <span>{{ __('dashboard.bans') }}</span>
                            </div>
                        </a>
                    </li>

                    <li class="{{ Request::is('*list/mutes*') ? 'active' : '' }}">
                        <a href="{{getAppSubDirectoryPath();}}/list/mutes" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-microphone-alt-slash fa-fw me-3"></i> <span>{{ __('dashboard.mutes') }}</span>
                            </div>
                        </a>
                    </li>
                    @if(env('APPEALS') == 'Enabled')
                    <li class="{{ Request::is('*appeals/create*') ? 'active' : '' }}">
                        <a href="{{ getAppSubDirectoryPath() }}/appeals/create" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-gavel fa-fw me-3"></i> <span>{{ __('Appeal Ban') }}</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if(env('REPORTS') == 'Enabled')
                    <li class="{{ Request::is('*reports/create*') ? 'active' : '' }}">
                        <a href="{{ getAppSubDirectoryPath() }}/reports/create" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-user-alt-slash fa-fw me-3"></i> <span>{{ __('Report Player') }}</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if(in_array(true, $onlyManageAdminPerms))
                        @if(env('APPEALS') == 'Enabled')
                        <li class="{{ Request::is('*appeals') ? 'active' : '' }}">
                            <a href="{{ getAppSubDirectoryPath() }}/appeals" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-list-alt fa-fw me-3"></i> <span>{{ __('Appeals') }}</span>
                                    @if(CommonHelper::appealCheck() > 0)
                                        <span class="badge badge-primary sidebar-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-message-circle badge-icon">
                                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                            </svg>
                                            {{CommonHelper::appealCheck()}}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </li>
                        @endif
                        @if(env('REPORTS') == 'Enabled')
                        <li class="{{ Request::is('*reports*') ? 'active' : '' }}">
                            <a href="{{ getAppSubDirectoryPath() }}/reports/list" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-list-alt fa-fw me-3"></i> <span>{{ __('Reports') }}</span>
                                    @if(CommonHelper::reportCheck() > 0)
                                        <span class="badge badge-primary sidebar-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-message-circle badge-icon">
                                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                            </svg>
                                            {{CommonHelper::reportCheck()}}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </li>
                        @endif
                    @endif
                </ul>
            </li>

            @if(env('SKINS') == 'Enabled')
                <li class="menu">
                    <a href="#weaponpaintsSection" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*weapons/skins*') || Request::is('*gloves/skins*') || Request::is('*agents/skins*') || Request::is('*music/kits*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-droplet"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                            <span>{{ __('dashboard.weaponpaints') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*weapons/skins*') || Request::is('*gloves/skins*') || Request::is('*agents/skins*') || Request::is('*music/kits*') ? 'show' : '' }}" id="weaponpaintsSection" data-bs-parent="#accordionExample">
                        <li class="{{ Request::is('*weapons/skins*') || Request::is('*gloves/skins*') || Request::is('*agents/skins*') || Request::is('*music/kits*') ? 'active' : '' }}">
                            <a href="{{getAppSubDirectoryPath()}}/weapons/skins" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-fire fa-fw me-3"></i><span>{{ __('dashboard.skins') }}</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(env('RANKS') == 'Enabled' || env('VIP') == 'Enabled')
                <li class="menu">
                    <a href="#statsSection" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*list/ranks*') || Request::is('*vip*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>                            <span>{{ __('admins.stats') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*list/ranks*') || Request::is('*vip*') ? 'show' : '' }}" id="statsSection" data-bs-parent="#accordionExample">
                        @if(env('RANKS') == 'Enabled')
                            <li class="{{ Request::is('*list/ranks*') ? 'active' : '' }}">
                                <a href="{{getAppSubDirectoryPath()}}/list/ranks" class="dropdown-toggle">
                                    <div class="">
                                        <i class="fas fa-trophy fa-fw me-3"></i><span>{{ __('admins.ranks') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(env('VIP') == 'Enabled')
                            <li class="{{ Request::is('*vip*') ? 'active' : '' }}">
                                <a href="{{getAppSubDirectoryPath()}}/vip" class="dropdown-toggle">
                                    <div class="">
                                        <i class="fas fa-crown fa-fw me-3"></i><span>{{ __('admins.VIP') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @php
                $sectionPermissions = [
                   PermissionsHelper::isSuperAdmin(),
                   PermissionsHelper::hasAdminCreatePermission(),
                   PermissionsHelper::hasAdminEditPermission(),
                   PermissionsHelper::hasAdminDeletePermission(),
                   PermissionsHelper::hasBanPermission(),
                   PermissionsHelper::hasMutePermission(),
               ];
            @endphp
            @if(in_array(true, $sectionPermissions))
                <li class="menu">
                    <a href="#adminSection" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*list/admins*') || Request::is('*ban/add*') || Request::is('*mute/add*') || Request::is('*list/groups*') || Request::is('*group/create*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>{{ __('admins.admin') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*list/admins*') || Request::is('*ban/add*') || Request::is('*mute/add*') || Request::is('*list/groups*') || Request::is('*group/create*') ? 'show' : '' }}" id="adminSection" data-bs-parent="#accordionExample">
                        @if(in_array(true, $onlyManageAdminPerms))
                            <li class="{{ Request::is('*list/admins*') ? 'active' : '' }}">
                                <a href="{{getAppSubDirectoryPath();}}/list/admins" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="fas fa-users-cog fa-fw me-3"></i><span>{{ __('admins.title') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(PermissionsHelper::hasBanPermission())
                            <li class="{{ Request::is('*ban/add*') ? 'active' : '' }}">
                                <a href="{{getAppSubDirectoryPath();}}/ban/add" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="fas fa-plus fa-fw me-3"></i><span>{{ __('admins.addBans') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(PermissionsHelper::hasMutePermission())
                            <li class="{{ Request::is('*mute/add*') ? 'active' : '' }}">
                                <a href="{{getAppSubDirectoryPath();}}/mute/add" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="fas fa-plus fa-fw me-3"></i><span>{{ __('admins.addMute') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(PermissionsHelper::isSuperAdmin() || PermissionsHelper::hasGroupCreatePermission() || PermissionsHelper::hasGroupDeletePermission() || PermissionsHelper::hasGroupEditPermission())
                            <li class="{{ Request::is('*list/groups*') ? 'active' : '' }}">
                                <a href="{{getAppSubDirectoryPath();}}/list/groups" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="fas fa-users fa-fw me-3"></i><span>{{ __('admins.allGroups') }}</span>
                                    </div>
                                </a>
                            </li>
                            @if(PermissionsHelper::hasGroupCreatePermission() || PermissionsHelper::isSuperAdmin())
                                <li class="{{ Request::is('*group/create*') ? 'active' : '' }}">
                                    <a href="{{getAppSubDirectoryPath();}}/group/create" aria-expanded="false" class="dropdown-toggle">
                                        <div class="">
                                            <i class="fas fa-plus fa-fw me-3"></i><span>{{ __('admins.createGroup') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endif
                        @if(PermissionsHelper::hasBanPermission() || env('DEMOS') == 'Enabled')
                            <li class="menu {{ Request::is('*demos*') ? 'active' : '' }}">
                                <a href="{{getAppSubDirectoryPath()}}/demos" class="dropdown-toggle">
                                    <div class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                        <span>{{ __('admins.demos') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if(PermissionsHelper::isSuperAdmin())
                <li class="menu">
                    <a href="#rconSection" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*rcon*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>                            <span>{{ __('admins.rcon') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*rcon*') ? 'show' : '' }}" id="rconSection" data-bs-parent="#accordionExample">
                        <li class="{{ Request::is('*rcon*') ? 'active' : '' }}">
                            <a href="{{getAppSubDirectoryPath();}}/rcon" aria-expanded="false" class="dropdown-toggle">
                                <div class=""><i class="fa fa-terminal fa-fw me-3"></i><span>{{ __('admins.rcon') }}</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu">
                    <a href="#settingsSection" data-bs-toggle="collapse" aria-expanded="{{ Request::is('*modules*') || Request::is('*settings') || Request::is('*settings/servers*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>                            <span>{{ __('admins.settings') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*modules*') || Request::is('*settings') || Request::is('*settings/servers*') ? 'show' : '' }}" id="settingsSection" data-bs-parent="#accordionExample">
                        <li class="{{ Request::is('*modules*') ? 'active' : '' }}">
                            <a href="{{getAppSubDirectoryPath();}}/modules" aria-expanded="false" class="dropdown-toggle">
                                <div class=""><i class="fa fa-cog fa-fw me-3"></i><span>{{ __('Modules') }}</span></div>
                            </a>
                        </li>
                        <li class="{{ Request::is('*settings') ? 'active' : '' }}">
                            <a href="{{getAppSubDirectoryPath();}}/settings" aria-expanded="false" class="dropdown-toggle">
                                <div class=""><i class="fa fa-cog fa-fw me-3"></i><span>{{ __('admins.panel') }}</span></div>
                            </a>
                        </li>
                        <li class="{{ Request::is('*settings/servers*') ? 'active' : '' }}">
                            <a href="{{getAppSubDirectoryPath();}}/settings/servers" aria-expanded="false" class="dropdown-toggle">
                                <div class=""><i class="fa fa-cog fa-fw me-3"></i><span>{{ __('dashboard.servers') }}</span></div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="menu">
                @if(!empty(Auth::user()))
                    <a href="{{getAppSubDirectoryPath();}}/auth/logout" aria-expanded="false" class="dropdown-toggle">
                        <div class=""><i class="fas fa-sign-out-alt fa-fw me-3"></i><span>{{ __('admins.logout') }}</span></div>
                    </a>
                @else
                    <a href="{{getAppSubDirectoryPath();}}/auth/steam" aria-expanded="false" class="dropdown-toggle">
                        <div class=""><i class="fab fa-steam fa-fw me-3"></i><span>{{ __('admins.login') }}</span></div>
                    </a>
                @endif
            </li>
            @if(count(customLinks()) > 0)
                <li class="menu">
                    <a href="#otherSection" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-minus">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>{{ __('admins.other') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="otherSection" data-bs-parent="#accordionExample">
                        @foreach(customLinks() as $link=>$title)
                            <li class="menu">
                                <a target="_blank" href="{{$link}}" aria-expanded="false" class="dropdown-toggle">
                                    <div class=""><i class="fas fa-external-link-alt"></i><span>{{$title}}</span></div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif

        </ul>

    </nav>

</div>
