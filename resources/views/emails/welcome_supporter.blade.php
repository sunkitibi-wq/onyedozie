<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Onyendozi Connect</title>
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
            color: #333333;
            line-height: 1.6;
        }
        .content h2 {
            font-size: 20px;
            color: #3a0ca3;
            margin-top: 0;
        }
        .content p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555555;
        }
        .cta-button {
            display: inline-block;
            background-color: #4361ee;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.15);
            transition: background-color 0.2s;
        }
        .cta-button:hover {
            background-color: #3a0ca3;
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
            <h2>Welcome to the movement, {{ $user->name }}!</h2>
            <p>Thank you for registering and joining Onyendozi Connect. We are a people-powered movement committed to building a brighter future, promoting accountability, and driving grassroots development.</p>
            
            <p>Your involvement is the key to our success. Whether you are volunteering in your community, coordinating activities, or helping get the word out, every action counts.</p>
            
            <p>Stay tuned for updates, upcoming community events, and campaign progress.</p>
            
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}" class="cta-button">Visit Our Platform</a>
            </div>
            
            <p>If you have any questions or would like to get more involved, feel free to reply to this email or reach out to us at info@onyendoziconnect.org.</p>
            
            <p>Best regards,<br><strong>The Onyendozi Connect Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Onyendozi Connect. All rights reserved.
        </div>
    </div>
</body>
</html>
