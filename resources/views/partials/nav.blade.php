<!--Main Navigation-->
<header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse ">
        <div class="position-sticky">
            <div class="list-group list-group-flush mx-3 mt-4">
                <a href="/" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init aria-current="true">
                    <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
                </a>
                <a href="/list/bans" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                    <i class="fas fa-ban fa-fw me-3"></i><span>Bans</span>
                </a>
                <a href="/list/mutes" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                    <i class="fas fa-microphone-alt-slash fa-fw me-3"></i><span>Mutes</span>
                </a>
                <a href="/list/admins" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                    <i class="fas fa-users-cog fa-fw me-3"></i><span>Admins</span>
                </a>
                <a href="/ban/add" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                    <i class="fas fa-plus fa-fw me-3"></i><span>Add Ban</span>
                </a>
                <a href="/mute/add" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                    <i class="fas fa-plus fa-fw me-3"></i><span>Add Mute</span>
                </a>
                @if(!empty(Auth::user()))
                    <a href="{{url('auth/logout')}}" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                        <i class="fas fa-sign-out-alt fa-fw me-3"></i><span>Logout</span>
                    </a>
                @else
                    <a href="{{url('auth/steam')}}" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                        <i class="fab fa-steam fa-fw me-3"></i><span>Login with Steam</span>
                    </a>
                @endif
            </div>
        </div>
    </nav>
    <!-- Sidebar -->

    <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg  fixed-top">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <img src="https://svgshare.com/i/15MM.svg" alt="css-bans" loading="lazy" />
            </a>
            <!-- Right links -->
            <ul class="navbar-nav ms-auto d-flex flex-row">
                <!-- Icon -->
                <li class="nav-item">
                <li class="nav-item align-items-center d-flex" >
                    <i class="fas fa-sun"></i>
                    <!-- Default switch -->
                    <div class="ms-2 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="themeStitcher" />
                    </div>
                    <i class="fas fa-moon"></i>
                </li>
                <!-- Icon -->
                <li class="nav-item me-3 mt-1 me-lg-0">
                    <a class="nav-link" href="#">
                        <i class="fab fa-github"></i>
                    </a>
                </li>

                <!-- Avatar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#"
                       id="navbarDropdownMenuLink" role="button" data-mdb-dropdown-init aria-expanded="false">
                        <img src="{{ Auth::user()?->avatar ?: 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg'}}" class="rounded-circle" height="22"
                             alt="" loading="lazy" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li>
                            @if(!empty(Auth::user()))
                                <a class="dropdown-item" href="{{url('auth/logout')}}">Logout</a>
                            @else
                                <a class="dropdown-item" href="{{url('auth/steam')}}">Login with steam</a>
                            @endif
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
</header>
<!--Main Navigation-->
