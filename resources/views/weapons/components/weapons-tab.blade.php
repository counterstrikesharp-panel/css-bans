<ul class="nav nav-tabs" id="weaponTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link {{ Request::is('*weapons/skins*') ? 'active' : '' }}" id="weapon-dropdown-tab" data-bs-toggle="modal" data-bs-target="#weaponModal" type="button">
            <a href="{{ !Request::is('*weapons/skins*') ? getAppSubDirectoryPath()."/weapons/skins" : 'javascript:void(0);'}}"><img src="https://raw.githubusercontent.com/Nereziel/cs2-WeaponPaints/main/website/img/skins/weapon_deagle-962.png" alt="Weapons" width="50" height="50"></a>
            @if(Request::is('*weapons/skins*'))
                <span class="modal-icon">
                    <i class="fas fa-chevron-down"></i>
                </span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::is('*agents/skins*') ? 'active' : '' }}" id="agents-tab-icon" data-bs-toggle="tab" data-bs-target="#agents-tab-pane" type="button" role="tab" aria-controls="agents-tab-pane" aria-selected="{{ Request::is('agents') ? 'true' : 'false' }}">
            <a href="{{getAppSubDirectoryPath()}}/agents/skins"><img src="https://raw.githubusercontent.com/daffyyyy/cs2-WeaponPaints/main/website/img/skins/agent-4726.png" alt="Agents" width="50" height="50"></a>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ Request::is('*gloves/skins*') ? 'active' : '' }}" id="glove-dropdown-tab" data-bs-toggle="modal" data-bs-target="#gloveModal" type="button">
            <a href="{{ !Request::is('*gloves/skins*') ? getAppSubDirectoryPath()."/gloves/skins" : 'javascript:void(0);'}}"><img src="https://raw.githubusercontent.com/daffyyyy/cs2-WeaponPaints/main/website/img/skins/studded_brokenfang_gloves-10085.png" alt="Gloves" width="50" height="50"></a>
            @if(Request::is('*gloves/skins*'))
                <span class="modal-icon">
                    <i class="fas fa-chevron-down"></i>
                </span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::is('*music/kits*') ? 'active' : '' }}" id="music-tab-icon" data-bs-toggle="tab" data-bs-target="#music-tab-pane" type="button" role="tab" aria-controls="music-tab-pane" aria-selected="{{ Request::is('music') ? 'true' : 'false' }}">
            <a href="{{getAppSubDirectoryPath()}}/music/kits"><img src="https://raw.githubusercontent.com/daffyyyy/cs2-WeaponPaints/main/website/img/skins/music_kit-3.png" alt="Music" width="50" height="50"></a>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ Request::is('*weapons/knives*') ? 'active' : '' }}" id="knife-dropdown-tab" data-bs-toggle="modal" data-bs-target="#knifeModal" type="button">
            <a href="{{ !Request::is('*weapons/knives*') ? getAppSubDirectoryPath()."/weapons/knives" : 'javascript:void(0);'}}"><img src="https://raw.githubusercontent.com/Nereziel/cs2-WeaponPaints/main/website/img/skins/weapon_knife_push-38.png" alt="Knives" width="50" height="50"></a>
            @if(Request::is('*weapons/knives*'))
                <span class="modal-icon">
                    <i class="fas fa-chevron-down"></i>
                </span>
            @endif
        </button>
    </li>
</ul>
