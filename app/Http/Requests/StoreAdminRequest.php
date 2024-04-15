<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'server_ids.*' => 'exists:sa_servers,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
            'ends' => 'required|date|after:today'
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
        ];
    }
}
