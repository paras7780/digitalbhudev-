<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Devotee Inquiry / Booking Alert</title>
</head>
<body style="font-family:'DM Sans',Arial,sans-serif;background-color:#fcf9f4;color:#29241f;margin:0;padding:24px 0;line-height:1.6">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="background:#ffffff;border:1px solid #ede4d5;border-radius:10px;overflow:hidden;box-shadow:0 4px 18px rgba(41,36,31,0.06);margin:0 auto">
        <!-- Header -->
        <tr>
            <td style="background:#201a15;padding:24px 30px;border-bottom:3px solid #b94e20">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <span style="font-size:26px;color:#d5a45c;vertical-align:middle;margin-right:8px">ॐ</span>
                            <strong style="color:#ffffff;font-size:18px;letter-spacing:0.02em">DigitalBhudev</strong>
                        </td>
                        <td align="right">
                            <span style="background:{{ $inquiry->type === 'booking' ? '#2e6930' : '#b94e20' }};color:#ffffff;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:bold;text-transform:uppercase;letter-spacing:0.05em">
                                {{ $inquiry->type === 'booking' ? 'Pooja Booking' : 'Contact Inquiry' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding:28px 30px">
                <h2 style="font-family:Georgia,serif;font-size:20px;color:#29241f;margin:0 0 12px">
                    {{ $inquiry->type === 'booking' ? 'New Pooja Booking Request Received' : 'New Contact Inquiry Received' }}
                </h2>
                <p style="font-size:14px;color:#6d675e;margin:0 0 20px">
                    A devotee has submitted a new inquiry through the DigitalBhudev portal. Details are provided below:
                </p>

                <!-- Details Table -->
                <table width="100%" border="0" cellspacing="0" cellpadding="10" style="background:#faf7f0;border:1px solid #ebdcc8;border-radius:8px;font-size:13px;margin-bottom:22px">
                    <tr>
                        <td width="35%" style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">Devotee Name:</td>
                        <td width="65%" style="color:#29241f;font-weight:bold;border-bottom:1px solid #eee1d0">{{ $inquiry->name }}</td>
                    </tr>
                    @if($inquiry->phone)
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">Phone / WhatsApp:</td>
                        <td style="color:#29241f;font-weight:bold;border-bottom:1px solid #eee1d0">{{ $inquiry->phone }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">Email Address:</td>
                        <td style="color:#29241f;border-bottom:1px solid #eee1d0">{{ $inquiry->email }}</td>
                    </tr>
                    @if($inquiry->city)
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">City / Location:</td>
                        <td style="color:#29241f;border-bottom:1px solid #eee1d0">{{ $inquiry->city }}</td>
                    </tr>
                    @endif
                    @if($inquiry->pooja_service)
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">Requested Ceremony:</td>
                        <td style="color:#b94e20;font-weight:bold;border-bottom:1px solid #eee1d0">{{ $inquiry->pooja_service }}</td>
                    </tr>
                    @endif
                    @if($inquiry->preferred_date)
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">Preferred Date:</td>
                        <td style="color:#29241f;border-bottom:1px solid #eee1d0">{{ $inquiry->preferred_date->format('d M Y') }}</td>
                    </tr>
                    @endif
                    @if($inquiry->bhudevProfile)
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">Requested Bhudev:</td>
                        <td style="color:#29241f;font-weight:bold;border-bottom:1px solid #eee1d0">{{ $inquiry->bhudevProfile->display_name }} ({{ $inquiry->bhudevProfile->city }})</td>
                    </tr>
                    @endif
                    @if($inquiry->subject && $inquiry->type !== 'booking')
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;border-bottom:1px solid #eee1d0">Subject:</td>
                        <td style="color:#29241f;border-bottom:1px solid #eee1d0">{{ $inquiry->subject }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color:#806f5f;font-weight:bold;vertical-align:top">Devotee Message:</td>
                        <td style="color:#29241f;line-height:1.5">{{ $inquiry->message ?: 'No additional message provided.' }}</td>
                    </tr>
                </table>

                <p style="font-size:12px;color:#8f7d6e;margin:0">
                    Received on: {{ $inquiry->created_at->format('d M Y, h:i A T') }}
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background:#f4ece0;padding:16px 30px;font-size:11px;color:#8f7d6e;text-align:center;border-top:1px solid #ebdcc8">
                DigitalBhudev — Connecting Vedic Tradition with Technology &bull; Confidential Administrative Notice
            </td>
        </tr>
    </table>
</body>
</html>
