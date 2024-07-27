<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'group_name' => 'required|regex:/^#.+$/',
            'server_ids' => 'required|array',
            'server_ids.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'all' && !DB::table('sa_servers')->where('id', $value)->exists()) {
                        $fail($attribute.' is invalid.');
                    }
                },
            ],
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
            'immunity' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'group_name.required' => 'The group name is required.',
            'group_name.regex' => 'The group name must start with #.',
            'server_id.required' => 'The server field is required.',
            'server_id.exists' => 'The selected server is invalid.',
            'permission_id.required' => 'The permission field is required.',
            'permission_id.exists' => 'The selected permission is invalid.',
            'immunity.required' => 'Immunity filed is required.',
        ];
    }
}
