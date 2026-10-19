
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We've Received Your Message - {{ $brand }}</title>
</head>

<body style="margin:0;padding:0;background:#f4eff7;font-family:Arial,Helvetica,sans-serif;color:#342444;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4eff7;">
    <tr>
        <td align="center" style="padding:30px 12px;">

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td align="center" style="background:#32134f;padding:35px 20px;">

                        <h1 style="margin:0;font-family:Georgia,serif;font-size:38px;font-weight:normal;color:#ffffff;">
                            ♡ {{ $brand }}
                        </h1>

                        <p style="margin:10px 0 0;color:#ead6ee;font-size:13px;letter-spacing:1px;">
                            Meaningful connections begin here
                        </p>

                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding:38px 32px;">

                        <!-- Success icon -->
                        <table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" width="64" height="64"
                                    style="width:64px;height:64px;background:#ede4f3;border-radius:50%;font-size:32px;color:#70338f;">
                                    &#10003;
                                </td>
                            </tr>
                        </table>

                        <h2 style="font-family:Georgia,serif;font-size:28px;line-height:1.3;text-align:center;color:#32134f;margin:22px 0 12px;">
                            We've Received<br>Your Message!
                        </h2>

                        <p style="text-align:center;color:#89768e;font-size:15px;margin:0 0 32px;">
                            Thank you for reaching out to {{ $brand }}.
                        </p>

                        <p style="font-size:16px;line-height:1.7;margin:0 0 16px;">
                            Dear <strong>{{ $name }}</strong>,
                        </p>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0 0 24px;">
                            Thank you for contacting <strong>{{ $brand }}</strong>.
                            Your message has been successfully submitted.
                            Our support team will review your enquiry and
                            get back to you as soon as possible.
                        </p>

                        <!-- Enquiry summary -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="background:#f3ecf7;border-radius:12px;">
                            <tr>
                                <td style="padding:22px 24px;">

                                    <p style="font-size:16px;font-weight:bold;color:#32134f;margin:0 0 18px;">
                                        Your Enquiry Summary
                                    </p>

                                    <p style="font-size:13px;color:#76677d;margin:0 0 6px;">
                                        Subject
                                    </p>

                                    <p style="font-size:15px;font-weight:bold;color:#32134f;margin:0 0 16px;overflow-wrap:anywhere;">
                                        {{ $subject }}
                                    </p>

                                    <div style="border-top:1px solid #ded0e7;margin-bottom:16px;"></div>

                                    <p style="font-size:13px;color:#76677d;margin:0 0 8px;">
                                        Your Message
                                    </p>

                                    <p style="font-size:14px;line-height:1.8;color:#55475e;margin:0;white-space:pre-wrap;overflow-wrap:anywhere;">{{ $messageText }}</p>

                                </td>
                            </tr>
                        </table>

                        <!-- Support text -->
                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:28px 0 16px;">
                            If you need to share additional information,
                            please feel free to contact our support team at
                            <a href="mailto:{{ $supportAddress }}"
                               style="color:#70338f;text-decoration:none;font-weight:bold;">
                                {{ $supportAddress }}
                            </a>.
                        </p>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0 0 26px;">
                            We appreciate your patience and look forward
                            to assisting you.
                        </p>

                        <!-- Button -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" bgcolor="#70338f"
                                    style="border-radius:10px;">
                                    <a href="{{ $siteUrl }}"
                                       style="display:block;padding:17px 20px;color:#ffffff;font-size:16px;font-weight:bold;text-decoration:none;">
                                        Visit {{ $brand }} &rarr;
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:30px 0 0;">
                            Warm regards,<br>
                            <strong style="color:#32134f;">Team {{ $brand }}</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="background:#f4edf6;padding:28px 24px;border-top:1px solid #e8ddec;">

                        <p style="font-family:Georgia,serif;font-size:16px;font-weight:bold;color:#32134f;margin:0 0 12px;">
                            Traditional Values. Brighter Tomorrows.
                        </p>

                        <p style="font-size:13px;margin:0 0 12px;">
                            <a href="mailto:{{ $supportAddress }}"
                               style="color:#793a9a;text-decoration:none;">
                                {{ $supportAddress }}
                            </a>
                        </p>

                        <p style="font-size:13px;margin:0 0 15px;">
                            <a href="{{ $siteUrl }}"
                               style="color:#793a9a;text-decoration:none;">
                                {{ parse_url($siteUrl, PHP_URL_HOST) }}
                            </a>
                        </p>

                        <p style="font-size:11px;color:#9b8ca2;margin:0;">
                            &copy; {{ date('Y') }} {{ $brand }}.
                            All rights reserved.
                        </p>

                    </td>
                </tr>
            </table>

            <p style="font-size:12px;line-height:1.6;color:#95869e;text-align:center;max-width:500px;margin:20px auto;">
                This is an automated confirmation of your contact form submission.
                If you did not submit this enquiry, please contact our support team.
            </p>

        </td>
    </tr>
</table>

</body>
</html>
