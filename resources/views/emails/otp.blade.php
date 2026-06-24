<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your OTP Verification Code</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #eef2f5;
        }
        .header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
            color: #333333;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-code {
            display: inline-block;
            font-size: 36px;
            font-weight: 800;
            color: #4361ee;
            background-color: #f0f4ff;
            padding: 12px 30px;
            border-radius: 8px;
            letter-spacing: 6px;
            margin-bottom: 30px;
            border: 1px dashed #4361ee;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
            border-top: 1px solid #eef2f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Onyendozi Connect</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Your verification code is shown below. Please use this code to complete your action.</p>
            <div class="otp-code">{{ $code }}</div>
            <p>This code will expire in {{ $expires }} minutes. If you did not request this code, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Onyendozi Connect. All rights reserved.
        </div>
    </div>
</body>
</html>
