<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #05bdff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f4f4f9;
            color: #888;
            padding: 10px 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #4caf50;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
    <title>{{ __('emails.subject.' . $appeal->status) }}</title>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{ __('emails.subject.' . $appeal->status) }}</h1>
    </div>
    <div class="content">
        <p>{{ __('emails.greeting', ['name' => $appeal->name]) }}</p>
        <p>
            {{ $appeal->status === 'approved' ?
            __('emails.body.APPROVED') :
            __('emails.body.REJECTED') }}
        </p>
        <p>{{ __('emails.regards') }}</p>
    </div>
    <div class="footer">
        <p>{!! __('emails.footer.copyright', ['year' => date('Y'), 'app_name' => config('app.name')]) !!}</p>
        <p>
            <a href="https://github.com/counterstrikesharp-panel/css-bans/">{{ __('emails.footer.github') }}</a>
        </p>
    </div>
</div>
</body>
</html>
