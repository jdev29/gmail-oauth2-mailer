<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background: #ffffff;
            margin: 20px auto;
            padding: 30px;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
        }
        .content p.justified {
            text-align: justify;
        }
        .content a {
            color: #001a82 !important;
            text-decoration: none;
        }
        .content a:hover,
        .content a:focus,
        .content a:active {
            color: #001f9d !important;
            text-decoration: none;
        }
        .content .expiration-date {
            color: #ff7700;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ $inline_images['logo']['cid'] }}" alt="{{ $inline_images['logo']['options']['alt'] }}" title="{{ $inline_images['logo']['options']['alt'] }}" style="max-width: 200px;">
        </div>
        <div class="content">
            <p>Hello <strong>{{$name}}</strong>,</p>
            <p class="justified">{{$message}}</p>
            <p>
                Greetings,<br>
                CST
            </p>
            <p>&nbsp;</p>

            @if ($attachments)
                <p>List of attachments:</p>
                <ul>
                    @foreach ($attachments as $attachment)
                        <li>{{ $attachment['name'] }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your company | All rights reserved</p>
        </div>
    </div>
</body>
</html>
