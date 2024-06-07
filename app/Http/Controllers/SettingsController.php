<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function showSettings()
    {
        // Read settings from the .env file or from a settings storage
        $settings = [
            'CSS-BANS' => [
                'DB_HOST' => env('DB_HOST'),
                'DB_PORT' => env('DB_PORT'),
                'DB_DATABASE' => env('DB_DATABASE'),
                'DB_USERNAME' => env('DB_USERNAME'),
                'DB_PASSWORD' => env('DB_PASSWORD'),
            ],
            'Modules' => [
                'RANKS' => env('RANKS'),
                'VIP' => env('VIP'),
                'SKINS' => env('SKINS'),
            ],
            'VIP Module' => [
                'DB_HOST_VIP' => env('DB_HOST_VIP'),
                'DB_DATABASE_VIP' => env('DB_DATABASE_VIP'),
                'DB_USERNAME_VIP' => env('DB_USERNAME_VIP'),
                'DB_PASSWORD_VIP' => env('DB_PASSWORD_VIP'),
                'DB_PORT_VIP' => env('DB_PORT_VIP'),
            ],
            'Ranks Module' => [
                'DB_HOST_RANKS' => env('DB_HOST_RANKS'),
                'DB_DATABASE_RANKS' => env('DB_DATABASE_RANKS'),
                'DB_USERNAME_RANKS' => env('DB_USERNAME_RANKS'),
                'DB_PASSWORD_RANKS' => env('DB_PASSWORD_RANKS'),
                'DB_PORT_RANKS' => env('DB_PORT_RANKS'),
            ],
            'Skins Module' => [
                'DB_HOST_SKINS' => env('DB_HOST_SKINS'),
                'DB_DATABASE_SKINS' => env('DB_DATABASE_SKINS'),
                'DB_USERNAME_SKINS' => env('DB_USERNAME_SKINS'),
                'DB_PASSWORD_SKINS' => env('DB_PASSWORD_SKINS'),
                'DB_PORT_SKINS' => env('DB_PORT_SKINS'),
            ],
            // Add other settings as needed
        ];

        return view('settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->all();

        // Update the .env file or settings storage
        foreach ($data as $key => $value) {
            $this->setEnvironmentValue($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    private function setEnvironmentValue($key, $value)
    {
        $path = base_path('.env');

        if (File::exists($path)) {
            $fileContent = File::get($path);

            if (strpos($fileContent, $key) !== false) {
                $fileContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $fileContent);
            } else {
                $fileContent .= "\n{$key}={$value}";
            }

            File::put($path, $fileContent);
        }
    }
}



