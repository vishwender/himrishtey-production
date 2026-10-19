
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Welcome to {{ $brand }}</title>
</head>

<body style="margin:0;padding:0;background-color:#f4eff7;font-family:Arial,Helvetica,sans-serif;color:#342444;">

<table role="presentation" cellpadding="0" cellspacing="0" border="0"
       width="100%" style="background-color:#f4eff7;">
    <tr>
        <td align="center" style="padding:30px 12px;">

            <!-- Main email container -->
            <table role="presentation" cellpadding="0" cellspacing="0"
                   border="0" width="100%"
                   style="max-width:600px;background-color:#ffffff;border-radius:16px;overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td align="center"
                        style="background-color:#32134f;padding:35px 20px;">

                        <h1 style="margin:0;font-family:Georgia,serif;font-size:38px;font-weight:normal;color:#ffffff;">
                            ♡ {{ $brand }}
                        </h1>

                        <p style="margin:10px 0 0;color:#ead6ee;font-size:13px;letter-spacing:1px;">
                            Meaningful connections begin here
                        </p>

                    </td>
                </tr>

                <!-- Welcome content -->
                <tr>
                    <td style="padding:38px 32px 20px;">

                        <p style="text-align:center;margin:0 0 12px;font-size:34px;color:#853c9d;">
                            &#9825;
                        </p>

                        <h2 style="font-family:Georgia,serif;text-align:center;font-size:29px;line-height:1.3;color:#32134f;margin:0 0 12px;">
                            Welcome to the<br>{{ $brand }} Family!
                        </h2>

                        <p style="text-align:center;color:#89768e;font-size:15px;margin:0 0 30px;">
                            Where meaningful relationships begin.
                        </p>

                        <p style="font-size:16px;line-height:1.7;margin:0 0 16px;">
                            Dear <strong>{{ $memberName }}</strong>,
                        </p>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0 0 24px;">
                            We're delighted to welcome you to
                            <strong>{{ $brand }}!</strong>
                            Your journey toward finding a meaningful life
                            partner begins here.
                        </p>

                        <!-- Registration details -->
                        <table role="presentation" width="100%"
                               cellpadding="0" cellspacing="0" border="0"
                               style="background-color:#f3ecf7;border-radius:12px;">
                            <tr>
                                <td style="padding:22px 24px;">

                                    <p style="margin:0 0 18px;font-size:16px;font-weight:bold;color:#32134f;">
                                        Your Registration Details
                                    </p>

                                    <table role="presentation" width="100%"
                                           cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="font-size:14px;color:#75647f;padding-bottom:12px;">
                                                Profile ID
                                            </td>
                                            <td align="right"
                                                style="font-size:15px;font-weight:bold;color:#32134f;padding-bottom:12px;">
                                                {{ $profileId }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"
                                                style="border-top:1px solid #decfe7;"></td>
                                        </tr>

                                        <tr>
                                            <td style="font-size:14px;color:#75647f;padding-top:12px;">
                                                Registration Status
                                            </td>
                                            <td align="right"
                                                style="font-size:14px;font-weight:bold;color:#66348a;padding-top:12px;">
                                                Successfully Registered
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>

                        <!-- Next steps -->
                        <h3 style="font-family:Georgia,serif;font-size:22px;color:#32134f;margin:30px 0 15px;">
                            Your Next Steps
                        </h3>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0 0 10px;">
                            &#10003; Complete your personal and family details.
                        </p>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0 0 10px;">
                            &#10003; Upload a clear and recent profile photograph.
                        </p>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0 0 10px;">
                            &#10003; Add your education, career and partner preferences.
                        </p>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0 0 28px;">
                            &#10003; Explore profiles that match your expectations.
                        </p>

                        <!-- CTA -->
                        <table role="presentation" width="100%"
                               cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center"
                                    style="background-color:#70338f;border-radius:10px;">

                                    <a href="{{ $profileUrl }}"
                                       style="display:block;padding:17px 20px;color:#ffffff;font-size:16px;font-weight:bold;text-decoration:none;">
                                        Complete Your Profile &rarr;
                                    </a>

                                </td>
                            </tr>
                        </table>

                        <p style="text-align:center;font-size:13px;color:#8c7b93;line-height:1.7;margin:24px 0 0;">
                            Your next chapter could begin with a single connection.
                        </p>

                        <!-- Closing -->
                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:30px 0 18px;">
                            At {{ $brand }}, we believe meaningful relationships
                            are built on trust, understanding and shared values.
                            We wish you all the best in finding your perfect match!
                        </p>

                        <p style="font-size:15px;line-height:1.8;color:#55475e;margin:0;">
                            Warm regards,<br>
                            <strong style="color:#32134f;">Team {{ $brand }}</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center"
                        style="background-color:#f4edf6;padding:28px 24px;border-top:1px solid #e8ddec;">

                        <p style="font-family:Georgia,serif;font-size:16px;font-weight:bold;color:#32134f;margin:0 0 12px;">
                            Traditional Values. Brighter Tomorrows.
                        </p>

                        <p style="font-size:13px;line-height:1.8;color:#76677d;margin:0 0 12px;">
                            Need assistance?<br>
                            <a href="mailto:{{ $supportAddress }}"
                               style="color:#793a9a;text-decoration:none;">
                                {{ $supportAddress }}
                            </a>
                        </p>

                        <p style="font-size:13px;margin:0 0 15px;">
                            <a href="{{ $siteUrl }}"
                               style="color:#793a9a;text-decoration:none;">
                                Visit {{ $brand }}
                            </a>
                        </p>

                        <p style="font-size:11px;color:#9b8ca2;margin:0;">
                            &copy; {{ date('Y') }} {{ $brand }}.
                            All rights reserved.
                        </p>

                    </td>
                </tr>

            </table>

            <!-- Email disclaimer -->
            <p style="font-size:12px;line-height:1.6;color:#95869e;text-align:center;max-width:500px;margin:20px auto;">
                You received this email because an account was registered
                with this email address on {{ $brand }}.
                If you did not register, please contact our support team.
            </p>

        </td>
    </tr>
</table>

</body>
</html>
