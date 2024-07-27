<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules()
    {
        return [
            'steam_id' => 'required|numeric|digits:17',
            'player_name' => 'required',
            'server_ids' => 'required|array',
            'server_ids.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'all' && !DB::table('sa_servers')->where('id', $value)->exists()) {
                        $fail($attribute.' is invalid.');
                    }
                },
            ],
            'permissions' => 'required_without:groups|array',
            'permissions.*' => 'exists:permissions,id',
            'ends' => 'required_without:permanent|date|after:today',
            'immunity' => 'required|numeric',
            'groups' => 'required_without:permissions|array'
        ];
    }

    public function messages()
    {
        return [
            'steamid.required' => 'The SteamID64 field is required.',
            'steamid.numeric' => 'The SteamID64 must be a number.',
            'steamid.digits:17' => 'The SteamID64 must be exactly 17 digits.',
            'player_name.required' => 'The player name is required.',
            'server_id.required' => 'The server field is required.',
            'server_id.exists' => 'The selected server is invalid.',
            'permission_id.required' => 'The permission field is required.',
            'permission_id.exists' => 'The selected permission is invalid.',
            'ends.after' => 'The end date must be a future date.',
            'immunity.required' => 'Immunity filed is required.',
        ];
    }
}
