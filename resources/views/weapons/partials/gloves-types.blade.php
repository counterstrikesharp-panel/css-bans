<!-- resources/views/weapons/partials/glove-types.blade.php -->
@foreach($gloves as $glove)
    <div class="col-md-3 mb-4">
        <a class="card style-6 glove-card" href="javascript:void(0);" data-skin-image="{{ $glove['image'] }}" data-skin-name="{{ $glove['paint_name'] }}">
            <span id="glove_{{ $glove['paint'] }}" class="glove_active  badge badge-danger">{{ $glove['is_applied'] ? __('skins.active') : '' }}</span>
            <div class="loader-skins"></div> <!-- Add loader -->
            <img src="{{ $glove['image'] }}" class="card-img-top lazy" alt="{{ $glove['paint_name'] }}" crossorigin="anonymous">
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 mb-4">
                        <b>{{ $glove['paint_name'] }}</b>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-primary apply-glove" data-weapon-defindex="{{ $glove['weapon_defindex'] }}" data-paint-id="{{ $glove['paint'] }}">
                            <i class="fas fa-cog"></i> {{ __('gloves.applyGlove') }}
                        </button>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach
