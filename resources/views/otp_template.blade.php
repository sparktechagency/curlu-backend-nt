<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Account</title>
</head>

<body lang="en"
    style="margin: 0; padding: 0; background-color: #eef1f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333;">

    <span style="display: none; font-size: 1px; color: #eef1f6;">Use this code to verify your identity. This OTP will
        expire in 10 minutes.</span>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color: #eef1f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width: 600px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.08); overflow: hidden;">

                    <!-- Minimal Header with App Name -->
                    <tr>
                        <td align="center"
                            style="background: linear-gradient(135deg, #F27405, #F27405); padding: 40px 20px;">
                            <h1 style="margin: 0; font-size: 26px; color: #ffffff; letter-spacing: 0.5px;">
                                {{ config('app.name') }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="font-size: 22px; margin-top: 0;">Hello,</h2>
                            @if ($email_type=="reset_password")
                                <p style="font-size: 16px; line-height: 1.6;">
                                    We received a request to reset the password for your
                                    <strong>{{ config('app.name') }}</strong> account.
                                </p>
                                <p style="font-size: 16px; line-height: 1.6;">
                                    Please use the following One-Time Password (OTP) to reset your password:
                                </p>
                            @else
                                <p style="font-size: 16px; line-height: 1.6;">
                                    We received a request to verify your identity for your
                                    <strong>{{ config('app.name') }}</strong> account.
                                </p>
                                <p style="font-size: 16px; line-height: 1.6;">
                                    Please use the following One-Time Password (OTP) to complete the verification
                                    process:
                                </p>
                            @endif
                            <!-- OTP Box -->
                            <div
                                style="font-size: 32px; font-weight: bold; text-align: center; color: #F27405; background-color: #f274051f; padding: 18px; border-radius: 10px; margin: 30px 0; letter-spacing: 6px;">
                                {{ $otp }}
                            </div>

                            <p style="font-size: 15px; color: #555;">This code is valid for <strong>10 minutes</strong>.
                                If you did not request this verification, please ignore this message or contact our
                                support team.</p>

                            <p style="margin-top: 30px; font-size: 16px;">Thanks, <br><strong>The
                                    {{ config('app.name') }} Team</strong></p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="background-color: #f9f9f9; padding: 20px; font-size: 13px; color: #888;">
                            &copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
