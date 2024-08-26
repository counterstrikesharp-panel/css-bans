{{--

/**
*
* Created a new component <x-navbar.style-vertical-menu/>.
*
*/

--}}
    <div class="header-container {{ $classes }}">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </a>

            <ul class="navbar-item flex-row ms-lg-auto ms-0">

                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <select id="language-dropdown" class="form-select">
                        <option {{ app()->getLocale() == 'en' ? 'selected' : '' }} value="en" data-image="{{ Vite::asset('resources/images/1x1/us.svg') }}">English</option>
                        <option {{ app()->getLocale() == 'de' ? 'selected' : '' }} value="de" data-image="{{ Vite::asset('resources/images/1x1/de.svg') }}">Deutsch</option>
                        <option {{ app()->getLocale() == 'es' ? 'selected' : '' }} value="es" data-image="{{ Vite::asset('resources/images/1x1/es.svg') }}">Español</option>
                        <option {{ app()->getLocale() == 'fr' ? 'selected' : '' }} value="fr" data-image="{{ Vite::asset('resources/images/1x1/fr.svg') }}">Français</option>
                        <option {{ app()->getLocale() == 'it' ? 'selected' : '' }} value="it" data-image="{{ Vite::asset('resources/images/1x1/it.svg') }}">Italiano</option>
                        <option {{ app()->getLocale() == 'ja' ? 'selected' : '' }} value="ja" data-image="{{ Vite::asset('resources/images/1x1/jp.svg') }}">日本語</option>
                        <option {{ app()->getLocale() == 'ko' ? 'selected' : '' }} value="ko" data-image="{{ Vite::asset('resources/images/1x1/kr.svg') }}">한국어</option>
                        <option {{ app()->getLocale() == 'pt_BR' ? 'selected' : '' }} value="pt_BR" data-image="{{ Vite::asset('resources/images/1x1/br.svg') }}">Brasileira</option>
                        <option {{ app()->getLocale() == 'pt_PT' ? 'selected' : '' }} value="pt_PT" data-image="{{ Vite::asset('resources/images/1x1/pt.svg') }}">Português</option>
                        <option {{ app()->getLocale() == 'ru' ? 'selected' : '' }} value="ru" data-image="{{ Vite::asset('resources/images/1x1/ru.svg') }}">Русский</option>
                        <option {{ app()->getLocale() == 'zh_CN' ? 'selected' : '' }} value="zh_CN" data-image="{{ Vite::asset('resources/images/1x1/cn.svg') }}">中文</option>
                        <option {{ app()->getLocale() == 'ro' ? 'selected' : '' }} value="ro" data-image="{{ Vite::asset('resources/images/1x1/ro.svg') }}">Românesc</option>
                        <option {{ app()->getLocale() == 'cz' ? 'selected' : '' }} value="cs" data-image="{{ Vite::asset('resources/images/1x1/cz.svg') }}">Čeština</option>
                        <option {{ app()->getLocale() == 'sk' ? 'selected' : '' }} value="sk" data-image="{{ Vite::asset('resources/images/1x1/sk.svg') }}">Slovenčina</option>
                        <option {{ app()->getLocale() == 'tr' ? 'selected' : '' }} value="tr" data-image="{{ Vite::asset('resources/images/1x1/tr.svg') }}">Türkçe</option>
                        <option {{ app()->getLocale() == 'uk' ? 'selected' : '' }} value="uk" data-image="{{ Vite::asset('resources/images/1x1/uk.svg') }}">українська</option>
                        <option {{ app()->getLocale() == 'hu' ? 'selected' : '' }} value="hu" data-image="{{ Vite::asset('resources/images/1x1/hu.svg') }}">Magyar</option>
                        <option {{ app()->getLocale() == 'se' ? 'selected' : '' }} value="se" data-image="{{ Vite::asset('resources/images/1x1/se.svg') }}">Svenska</option>
                        <option {{ app()->getLocale() == 'bg' ? 'selected' : '' }} value="bg" data-image="{{ Vite::asset('resources/images/1x1/bg.svg') }}">Български</option>
                    </select>
                </li>
                <li class="nav-item nav-link theme-toggle-item">
                    <a class="nav-link" href="https://github.com/counterstrikesharp-panel/css-bans">
                        <i class="fab fa-github"></i>
                    </a>
                </li>

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                <img alt="avatar" src="{{ Auth::user()?->avatar ?: Vite::asset('resources/images/profile-30.png') }}"  class="rounded-circle">
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="emoji me-2">
                                    &#x1F44B;
                                </div>
                                <div class="media-body">
                                    <h5>{{Auth::user()?->name ? : __('admins.guest') }}</h5>
                                    <p>{{Auth::user()?->steam_id ? :  __('admins.user') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            @if(!empty(Auth::user()))
                                <a href="{{env('VITE_SITE_DIR')}}/auth/logout">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>{{__('admins.logout')}}</span>
                                </a>
                            @else
                                <a href="{{env('VITE_SITE_DIR')}}/auth/steam">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="1080" height="1080" viewBox="0 0 1080 1080" xml:space="preserve"> <desc>Created with Fabric.js 5.2.4</desc> <defs> </defs> <rect x="0" y="0" width="100%" height="100%" fill="transparent"/> <g transform="matrix(1 0 0 1 540 540)" id="471a84a4-694f-4505-bcd0-b37583c433ba"> <rect style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1; visibility: hidden;" vector-effect="non-scaling-stroke" x="-540" y="-540" rx="0" ry="0" width="1080" height="1080"/> </g> <g transform="matrix(1 0 0 1 540 540)" id="6f3dd9d1-8893-4f52-9dde-137773428d8e"> </g> <g transform="matrix(13.45 0 0 13.45 552.4 555.34)"> <g style="" vector-effect="non-scaling-stroke"> <g transform="matrix(1 0 0 1 0.21 -14.88)"> <ellipse style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(158,158,158); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke" cx="0" cy="0" rx="18.519" ry="20.634"/> </g> <g transform="matrix(1 0 0 1 0 20.02)"> <polygon style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(158,158,158); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke" points="-17.95,-15.45 -17.28,-15.45 -16.87,-15.35 -16.46,-15.19 -16.04,-14.99 -15.68,-14.73 -15.22,-14.37 -14.8,-13.9 -14.34,-13.28 -13.88,-12.61 -13.41,-11.84 -13,-10.91 -12.54,-9.98 -12.12,-8.95 -11.71,-7.81 -11.35,-6.68 -10.99,-5.55 -10.63,-4.31 -10.27,-3.12 -9.9,-1.94 -9.54,-0.7 -9.23,0.49 -8.87,1.67 -8.46,2.81 -8.1,3.95 -7.63,5.03 -7.12,6.17 -6.55,7.19 -5.78,8.28 -4.8,9.36 -3.51,10.34 -1.85,11.01 0,11.27 1.91,11.01 3.56,10.29 4.9,9.31 5.83,8.23 6.55,7.14 7.17,6.06 7.69,4.92 8.1,3.84 8.51,2.66 8.87,1.52 9.23,0.34 9.6,-0.85 9.96,-2.09 10.32,-3.33 10.68,-4.51 11.04,-5.7 11.45,-6.89 11.86,-8.02 12.28,-9.11 12.74,-10.14 13.21,-11.06 13.72,-11.94 14.19,-12.72 14.7,-13.39 15.22,-13.95 15.68,-14.42 16.15,-14.73 16.66,-15.04 17.13,-15.24 17.69,-15.4 18.31,-15.45 19.24,-15.5 19.86,-15.45 20.38,-15.4 20.89,-15.35 21.41,-15.24 21.87,-15.14 22.39,-15.04 22.85,-14.88 23.27,-14.68 23.73,-14.52 24.14,-14.32 24.55,-14.06 24.92,-13.85 25.28,-13.59 25.64,-13.33 26,-13.03 26.31,-12.77 26.62,-12.46 26.88,-12.15 27.13,-11.84 27.39,-11.53 27.6,-11.22 27.81,-10.91 28.01,-10.65 28.16,-10.34 28.27,-10.09 28.37,-9.78 28.48,-9.57 28.53,-9.31 28.58,-9.16 28.63,-8.95 28.63,-8.85 28.63,-8.64 28.63,11.73 28.63,12.2 28.58,12.56 28.48,12.92 28.37,13.23 28.22,13.54 28.01,13.85 27.81,14.11 27.55,14.37 27.24,14.62 26.93,14.88 26.62,15.04 26.26,15.19 25.9,15.34 25.53,15.45 25.12,15.5 24.61,15.5 -24.61,15.5 -25.12,15.5 -25.48,15.45 -25.9,15.34 -26.26,15.19 -26.62,15.04 -26.93,14.88 -27.24,14.62 -27.5,14.37 -27.75,14.11 -27.96,13.85 -28.16,13.54 -28.32,13.23 -28.42,12.92 -28.53,12.56 -28.58,12.2 -28.63,11.73 -28.63,-8.64 -28.63,-8.8 -28.58,-8.85 -28.58,-9 -28.53,-9.16 -28.47,-9.36 -28.37,-9.57 -28.27,-9.83 -28.11,-10.09 -27.91,-10.4 -27.7,-10.7 -27.5,-11.01 -27.24,-11.32 -26.93,-11.63 -26.62,-11.94 -26.31,-12.25 -25.95,-12.56 -25.59,-12.87 -25.17,-13.18 -24.76,-13.44 -24.3,-13.7 -23.83,-13.95 -23.37,-14.21 -22.9,-14.42 -22.39,-14.62 -21.87,-14.83 -21.3,-14.99 -20.79,-15.14 -20.22,-15.24 -19.66,-15.35 -19.03,-15.4 -18.47,-15.45 "/> </g> </g> </g> </svg> <span>{{__('admins.login')}}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>

@vite(['resources/js/nav.js'])
