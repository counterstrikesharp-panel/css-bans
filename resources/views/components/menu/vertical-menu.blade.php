{{--

/**
*
* Created a new component <x-menu.vertical-menu/>.
*
*/d

--}}
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

            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>SERVER</span></div>
            </li>

            <li class="menu {{ Request::is('*list/bans*') ? 'active' : '' }}">
                <a href="{{getAppSubDirectoryPath();}}/list/bans" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-ban fa-fw me-3"></i>
                        <span>{{ __('dashboard.bans') }}</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ Request::is('*list/mutes*') ? 'active' : '' }}">
                <a href="{{getAppSubDirectoryPath();}}/list/mutes" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-microphone-alt-slash fa-fw me-3"></i> <span>{{ __('dashboard.mutes') }}</span>
                    </div>
                </a>
            </li>
            <li class="menu {{ Request::is('*appeals/create*') ? 'active' : '' }}">
                <a href="{{ getAppSubDirectoryPath() }}/appeals/create" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-gavel fa-fw me-3"></i> <span>Appeal Ban</span>
                    </div>
                </a>
            </li>
            @if(in_array(true, $onlyManageAdminPerms))
                <li class="menu {{ Request::is('*appeals') ? 'active' : '' }}">
                    <a href="{{ getAppSubDirectoryPath() }}/appeals" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <i class="fas fa-list-alt fa-fw me-3"></i> <span>Appeals</span>
                            @if(CommonHelper::appealCheck() > 0)
                                <span class="badge badge-primary sidebar-label"><svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-message-circle badge-icon"><path
                                            d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                            {{CommonHelper::appealCheck()}}
                                        </span>
                            @endif
                        </div>
                    </a>
                </li>
            @endif
            <li class="menu {{ Request::is('*reports/create*') ? 'active' : '' }}">
                <a href="{{ getAppSubDirectoryPath() }}/reports/create" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-user-alt-slash fa-fw me-3"></i> <span>Report Player</span>
                    </div>
                </a>
            </li>
            @if(in_array(true, $onlyManageAdminPerms))
                <li class="menu {{ Request::is('*reports/list*') ? 'active' : '' }}">
                    <a href="{{ getAppSubDirectoryPath() }}/reports/list" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <i class="fas fa-exclamation-triangle fa-fw me-3"></i> <span>Reports</span>
                                 @if(CommonHelper::reportCheck() > 0)
                                    <span class="badge badge-primary sidebar-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-message-circle badge-icon">
                                            <path
                                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                        </svg>
                                        {{ CommonHelper::reportCheck() }}
                                    </span>
                            @endif
                        </div>
                    </a>
                </li>
            @endif
            @if(env('SKINS') == 'Enabled')
                <li class="menu menu-heading">
                    <div class="heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-minus">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>{{ __('dashboard.weaponpaints') }}</span></div>
                </li>
                <li class="menu {{ Request::is('*weapons/skins*') || Request::is('*gloves/skins*') || Request::is('*agents/skins*') || Request::is('*music/kits*') ? "active" : "" }}">
                    <a href="{{getAppSubDirectoryPath()}}/weapons/skins" class="dropdown-toggle">
                        <div class="">
                            <i class="fas fa-fire fa-fw me-3"></i><span>{{ __('dashboard.skins') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(env('RANKS') == 'Enabled' || env('VIP') == 'Enabled')
                <li class="menu menu-heading">
                    <div class="heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-minus">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>{{ __('admins.stats') }}</span></div>
                </li>

                @if(env('RANKS') == 'Enabled')
                    <li class="menu {{ Request::is('*list/ranks*') ? "active" : "" }}">
                        <a href="{{getAppSubDirectoryPath()}}/list/ranks" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-trophy fa-fw me-3"></i><span>{{ __('admins.ranks') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if(env('VIP') == 'Enabled')
                    <li class="menu {{ Request::is('*vip*') ? "active" : "" }}">
                        <a href="{{getAppSubDirectoryPath()}}/vip" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-crown fa-fw me-3"></i><span>{{ __('admins.VIP') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
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
                <li class="menu menu-heading">
                    <div class="heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-minus">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>{{ __('admins.admin') }}</span></div>
                </li>
                @if(in_array(true, $onlyManageAdminPerms))
                    <li class="menu {{ Request::is('*admin*') ? 'active' : '' }}">
                        <a href="{{getAppSubDirectoryPath();}}/list/admins" aria-expanded="false"
                           class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-users-cog fa-fw me-3"></i><span>{{ __('admins.title') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if(PermissionsHelper::hasBanPermission())
                    <li class="menu {{ Request::is('*ban/add*') ? 'active' : '' }}">
                        <a href="{{getAppSubDirectoryPath();}}/ban/add" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-plus fa-fw me-3"></i><span>{{ __('admins.addBans') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if(PermissionsHelper::hasMutePermission())
                    <li class="menu {{ Request::is('*mute/add*') ? 'active' : '' }}">
                        <a href="{{getAppSubDirectoryPath();}}/mute/add" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-plus fa-fw me-3"></i><span>{{ __('admins.addMute') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if(PermissionsHelper::isSuperAdmin() || PermissionsHelper::hasGroupCreatePermission() || PermissionsHelper::hasGroupDeletePermission() || PermissionsHelper::hasGroupEditPermission())
                    <li class="menu {{ Request::is('*list/groups*') ? 'active' : '' }}">
                        <a href="{{getAppSubDirectoryPath();}}/list/groups" aria-expanded="false"
                           class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-users fa-fw me-3"></i><span>{{ __('admins.allGroups') }}</span>
                            </div>
                        </a>
                    </li>
                    @if(PermissionsHelper::hasGroupCreatePermission() || PermissionsHelper::isSuperAdmin())
                        <li class="menu {{ Request::is('*group/create*') ? 'active' : '' }}">
                            <a href="{{getAppSubDirectoryPath();}}/group/create" aria-expanded="false"
                               class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-plus fa-fw me-3"></i><span>{{ __('admins.createGroup') }}</span>
                                </div>
                            </a>
                        </li>
                    @endif
                @endif
            @endif
            @if(PermissionsHelper::isSuperAdmin())
                <li class="menu menu-heading">
                    <div class="heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-minus">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>{{ __('admins.rcon') }}</span></div>
                </li>
                <li class="menu {{ Request::is('*rcon*') ? 'active' : '' }}">
                    <a href="{{getAppSubDirectoryPath();}}/rcon" aria-expanded="false" class="dropdown-toggle">
                        <div class=""><i class="fa fa-terminal fa-fw me-3"></i><span>{{ __('admins.rcon') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(PermissionsHelper::isSuperAdmin())
                <li class="menu menu-heading">
                    <div class="heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-minus">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>{{ __('admins.settings') }}</span></div>
                </li>
                <li class="menu {{ Request::is('*settings') ? 'active' : '' }}">
                    <a href="{{getAppSubDirectoryPath();}}/settings" aria-expanded="false" class="dropdown-toggle">
                        <div class=""><i class="fa fa-cog fa-fw me-3"></i><span>{{ __('admins.panel') }}</span></div>
                    </a>
                </li>
                <li class="menu {{ Request::is('*settings/servers*') ? 'active' : '' }}">
                    <a href="{{getAppSubDirectoryPath();}}/settings/servers" aria-expanded="false"
                       class="dropdown-toggle">
                        <div class=""><i class="fa fa-cog fa-fw me-3"></i><span>{{ __('dashboard.servers') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>{{ __('dashboard.steam') }}</span></div>
            </li>
            <li class="menu">
                @if(!empty(Auth::user()))
                    <a href="{{getAppSubDirectoryPath();}}/auth/logout" aria-expanded="false" class="dropdown-toggle">

                        <div class=""><i
                                class="fas fa-sign-out-alt fa-fw me-3"></i><span>{{ __('admins.logout') }}</span></div>
                    </a>
                @else
                    <a href="{{getAppSubDirectoryPath();}}/auth/steam" aria-expanded="false" class="dropdown-toggle">
                        <div class=""><i class="fab fa-steam fa-fw me-3"></i><span>{{ __('admins.login') }}</span></div>
                    </a>
                @endif
            </li>

            @if(count(customLinks()) > 0)
                <li class="menu menu-heading">
                    <div class="heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-minus">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>{{ __('admins.othger') }}</span></div>
                </li>
                @foreach(customLinks() as $link=>$title)
                    <li class="menu">
                        <a target="_blank" href="{{$link}}" aria-expanded="false" class="dropdown-toggle">
                            <div class=""><i class="fas fa-external-link-alt"></i>
                                <span>{{$title}}</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            @endif

        </ul>

    </nav>

</div>
