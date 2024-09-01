<?php

namespace App\Http\Controllers;

// app/Http/Controllers/SettingsController.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
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
                'DISCORD_WEBHOOK' => env('DISCORD_WEBHOOK')
            ],
            'Modules' => [
                'RANKS' => env('RANKS'),
                'VIP' => env('VIP'),
                'SKINS' => env('SKINS'),
                'K4LegacySupport' => env('K4LegacySupport')
            ],
            'VIP Module' => [
                'DB_HOST_VIP' => env('DB_HOST_VIP'),
                'DB_DATABASE_VIP' => env('DB_DATABASE_VIP'),
                'DB_USERNAME_VIP' => env('DB_USERNAME_VIP'),
                'DB_PASSWORD_VIP' => env('DB_PASSWORD_VIP'),
                'DB_PORT_VIP' => env('DB_PORT_VIP'),
            ],
            'Skins Module' => [
                'DB_HOST_SKINS' => env('DB_HOST_SKINS'),
                'DB_DATABASE_SKINS' => env('DB_DATABASE_SKINS'),
                'DB_USERNAME_SKINS' => env('DB_USERNAME_SKINS'),
                'DB_PASSWORD_SKINS' => env('DB_PASSWORD_SKINS'),
                'DB_PORT_SKINS' => env('DB_PORT_SKINS'),
            ],
            'SMTP Settings' => [
                'MAIL_HOST' => env('MAIL_HOST'),
                'MAIL_PORT' => env('MAIL_PORT'),
                'MAIL_USERNAME' => env('MAIL_USERNAME'),
                'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
                'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
            ],
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

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $email = $request->input('test_email');

        // Temporarily set mail configuration
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
        ]);

        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', config('app.name'));

        try {
            Mail::raw('This is a test email.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email');
            });

            return redirect()->back()->with('success', 'Test email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
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




