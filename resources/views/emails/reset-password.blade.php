<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your VidsMotion Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            border-radius: 12px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .title {
            color: #1f2937;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 16px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 16px;
            color: #374151;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .security-note {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }
        .security-note h3 {
            color: #92400e;
            margin: 0 0 8px 0;
            font-size: 16px;
        }
        .security-note p {
            color: #92400e;
            margin: 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .footer a {
            color: #7c3aed;
            text-decoration: none;
        }
        .expiry {
            background: #f3f4f6;
            border-radius: 6px;
            padding: 12px;
            margin: 16px 0;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">VM</div>
            <h1 class="title">Reset Your Password</h1>
            <p class="subtitle">VidsMotion AI Video Generator</p>
        </div>

        <div class="content">
            <p>Hello {{ $user->name ?? 'there' }},</p>

            <p>We received a request to reset your password for your VidsMotion account. If you made this request, click the button below to reset your password:</p>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Reset My Password</a>
            </div>

            <div class="expiry">
                <strong>⏰ This link will expire in 60 minutes</strong>
            </div>

            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <p style="word-break: break-all; background: #f3f4f6; padding: 12px; border-radius: 6px; font-family: monospace; font-size: 14px;">
                {{ $url }}
            </p>

            <div class="security-note">
                <h3>🔒 Security Notice</h3>
                <p>If you didn't request a password reset, please ignore this email. Your password will remain unchanged. For security reasons, this link will expire in 60 minutes.</p>
            </div>

            <p>If you're having trouble with the link above, you can also:</p>
            <ul>
                <li>Try copying and pasting the link into a new browser window</li>
                <li>Make sure you're using the same email address you registered with</li>
                <li>Check your spam folder if you don't see this email</li>
            </ul>
        </div>

        <div class="footer">
            <p>This email was sent from VidsMotion AI Video Generator</p>
            <p>If you have any questions, please contact our support team.</p>
            <p>
                <a href="{{ config('app.url') }}">Visit VidsMotion</a> |
                <a href="{{ config('app.url') }}/contact">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>
