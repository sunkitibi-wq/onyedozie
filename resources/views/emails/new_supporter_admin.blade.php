<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Supporter Registration</title>
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
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 30px;
            color: #333333;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th, .details-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eef2f5;
        }
        .details-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555555;
            width: 35%;
        }
        .details-table td {
            color: #333333;
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
            <p>Hello Admin,</p>
            <p>A new supporter/coordinator has just registered on the frontend. Here are the details of the registration:</p>
            
            <table class="details-table">
                <tr>
                    <th>Name</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $user->phone ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <th>Role Assigned</th>
                    <td>{{ $user->roles->first()?->name ?? 'Supporter' }}</td>
                </tr>
                <tr>
                    <th>LGA</th>
                    <td>{{ $user->lga?->name ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <th>Ward</th>
                    <td>{{ $user->ward?->name ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <th>Polling Unit</th>
                    <td>{{ $user->pollingUnit?->name ?? 'Not specified' }}</td>
                </tr>
            </table>

            <p>Please log in to the admin panel to review and manage registrations as needed.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Onyendozi Connect. All rights reserved.
        </div>
    </div>
</body>
</html>
