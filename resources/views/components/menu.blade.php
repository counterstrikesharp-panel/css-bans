@if(PermissionsHelper::isSuperAdmin())
    <li class="menu {{ Request::is('admin/logs*') ? 'active' : '' }}">
        <a href="{{ route('admin.logs') }}" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                <span>Admin Activity Logs</span>
            </div>
        </a>
    </li>
@endif