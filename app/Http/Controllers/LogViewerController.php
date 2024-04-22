<?php

namespace App\Http\Controllers;

class LogViewerController extends Controller
{
    public function show()
    {
        $logPath = storage_path('logs/laravel.log');
        $logContent = '';

        if (file_exists($logPath)) {
            $lines = file($logPath);
            foreach ($lines as $line) {
                $logContent .= $this->extractLogMessage($line);
            }
        }

        return view('logs', ['logContent' => $logContent]);
    }

    private function extractLogMessage($line)
    {
        // Enable Stack trace
        if(env('APP_DEBUG') === true){
            return $line;
        }
        if (strpos($line, '#') === 0) {
            return null;
        }
        //Disable Stack Trace
        return strstr($line, 'Stack trace:', true) ?: $line;
    }
}
