<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cron Job Alert</title>
</head>
<body style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;background-color:#f9f5ed;margin:0;padding:24px;color:#29241f;">
    <table style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #ebd9c2;border-radius:8px;overflow:hidden;box-shadow:0 4px 14px rgba(0,0,0,0.06);width:100%;">
        <tr>
            <td style="background:#b94e20;padding:20px 24px;color:#ffffff;">
                <h1 style="margin:0;font-size:20px;font-weight:700;">ॐ DigitalBhudev — Scheduled Task Alert</h1>
                <p style="margin:4px 0 0;font-size:13px;color:#ffd8c4;">Automated System Notification</p>
            </td>
        </tr>
        <tr>
            <td style="padding:28px 24px;">
                <div style="background:#fdeeee;border:1px solid #f8c9c9;border-radius:6px;padding:14px 18px;margin-bottom:20px;color:#c02b2b;">
                    <strong style="font-size:15px;display:block;margin-bottom:4px;">⚠ Daily Cron Execution Failed</strong>
                    <span style="font-size:13px;">The daily scheduled task encountered an error during automated synchronization.</span>
                </div>

                <table style="width:100%;font-size:13px;border-collapse:collapse;margin-bottom:24px;">
                    <tr>
                        <td style="padding:8px 0;font-weight:700;color:#777168;width:130px;">Task Name:</td>
                        <td style="padding:8px 0;"><strong>{{ $commandName }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:700;color:#777168;">Failure Timestamp:</td>
                        <td style="padding:8px 0;">{{ $failedAt }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:700;color:#777168;vertical-align:top;">Diagnostic Details:</td>
                        <td style="padding:8px 0;color:#c02b2b;background:#fcf9f5;padding:10px;border-radius:4px;border:1px solid #ebd9c2;font-family:monospace;font-size:12px;line-height:1.5;">
                            {{ strip_tags($errorMessage) }}
                        </td>
                    </tr>
                </table>

                <h3 style="font-size:14px;margin:0 0 8px;color:#29241f;">Recommended Administrative Actions:</h3>
                <ol style="margin:0 0 24px;padding-left:20px;font-size:13px;line-height:1.7;color:#554c41;">
                    <li>Review the operational server logs in the storage directory.</li>
                    <li>Open your Admin Control Panel and inspect the current Panchang and Rashifal records.</li>
                    <li>Use the manual sync action in the Admin Control Panel to refresh data.</li>
                </ol>

                <p style="font-size:12px;color:#8f7d6e;border-top:1px solid #eee5d8;padding-top:16px;margin:0;">
                    This is an internal operational alert. Public visitors are protected as cached records remain active in the database.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
