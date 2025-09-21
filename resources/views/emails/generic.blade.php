<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText ?? 'Notification' }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        .content {
            font-size: 15px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 18px;
            background-color: #3490dc;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #aaa;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">{{ $subjectText ?? 'Hello!' }}</div>

        <div class="content">
            {!! nl2br(e($body ?? '')) !!}
        </div>

        @isset($buttonUrl)
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ $buttonUrl }}" class="button">
                    {{ $buttonText ?? 'View Details' }}
                </a>
            </div>
        @endisset

        <div class="footer">
            &copy; {{ date('Y') }} Your App. All rights reserved.
        </div>
    </div>
</body>
</html>
