<!-- resources/views/weapons/partials/skin-types.blade.php -->
@foreach($skins as $skin)
    <div class="col-md-3 mb-4">
        <a class="card style-6" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#skinPreviewModal" data-skin-image="{{ $skin['image'] }}" data-skin-name="{{ $skin['paint_name'] }}">
            <span id="skin_{{$skin['paint']}}" class="skin_active badge badge-danger">{{ $skin['is_applied'] ? __('skins.active') : '' }}</span>
            <div class="loader-skins"></div> <!-- Add loader -->
            <img src="{{ $skin['image'] }}" class="card-img-top lazy" alt="{{ $skin['paint_name'] }}" crossorigin="anonymous">
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 mb-4">
                        <b>{{ $skin['paint_name'] }}</b>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-primary apply-skin" data-bs-toggle="modal" data-bs-target="#applySkinModal" data-weapon-defindex="{{ $skin['weapon_defindex'] }}" data-weapon-paint-id="{{ $skin['paint'] }}" data-wear="{{ $skin['wear'] ?? '' }}" data-seed="{{ $skin['seed'] ?? '' }}" data-weapon-name="{{ $skin['weapon_name'] }}">
                            <i class="fas fa-cog"></i> {{ __('skins.applySkin') }}
                        </button>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach
