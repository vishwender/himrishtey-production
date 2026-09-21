<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment Successful | Himrishtey</title>
    <script>try { document.documentElement.dataset.theme = localStorage.getItem('hr-theme') || 'light'; } catch (e) {}</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --app-primary: #6d4aff;
            --app-primary-dark: #5435d6;
            --app-ink: #202034;
            --app-muted: #77778c;
            --app-surface: #ffffff;
            --app-canvas: #f7f6fc;
            --app-border: #ebe9f4;
            --success: #22a06b;
            --success-light: #eaf9f2;
        }

        [data-theme="dark"] {
            --app-ink: #f3edf7;
            --app-muted: #c4b7ca;
            --app-surface: #211d29;
            --app-canvas: #15121b;
            --app-border: #3b3447;
            --success-light: rgba(34, 160, 107, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "DM Sans", sans-serif;
            color: var(--app-ink);
            background:
                radial-gradient(circle at top left,
                    rgba(109, 74, 255, 0.12),
                    transparent 32%),
                radial-gradient(circle at bottom right,
                    rgba(109, 74, 255, 0.08),
                    transparent 30%),
                var(--app-canvas);

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 18px;
        }

        .success-wrapper {
            width: 100%;
            max-width: 620px;
        }

        .brand {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand-name {
            font-family: "Outfit", sans-serif;
            font-size: 30px;
            font-weight: 700;
            color: var(--app-primary);
            letter-spacing: -0.5px;
        }

        .brand-tagline {
            margin-top: 4px;
            font-size: 13px;
            color: var(--app-muted);
        }

        .success-card {
            background: var(--app-surface);
            border: 1px solid var(--app-border);
            border-radius: 26px;
            padding: 48px 45px 42px;
            text-align: center;

            box-shadow:
                0 20px 60px rgba(36, 27, 75, 0.08),
                0 4px 15px rgba(36, 27, 75, 0.04);
        }

        .success-icon-wrapper {
            width: 88px;
            height: 88px;
            margin: 0 auto 24px;

            border-radius: 50%;
            background: var(--success-light);

            display: flex;
            align-items: center;
            justify-content: center;

            animation: popIn .55s ease forwards;
        }

        .success-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;

            background: var(--success);

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            font-size: 28px;
            font-weight: 700;

            box-shadow: 0 8px 20px rgba(34, 160, 107, 0.22);
        }

        .success-title {
            font-family: "Outfit", sans-serif;
            font-size: 32px;
            line-height: 1.2;
            font-weight: 700;
            color: var(--app-ink);
            margin-bottom: 10px;
        }

        .success-message {
            max-width: 470px;
            margin: 0 auto;
            color: var(--app-muted);
            font-size: 15px;
            line-height: 1.7;
        }

        .membership-box {
            margin-top: 30px;
            padding: 20px;

            background: #faf9ff;
            border: 1px solid #ece8ff;
            border-radius: 17px;

            text-align: left;
        }

        .membership-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--app-muted);
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 5px;
        }

        .membership-name {
            font-family: "Outfit", sans-serif;
            font-size: 21px;
            font-weight: 600;
            color: var(--app-ink);
        }

        .membership-status {
            margin-top: 12px;
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 6px 11px;

            border-radius: 30px;

            background: var(--success-light);
            color: var(--success);

            font-size: 12px;
            font-weight: 600;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: var(--success);
            border-radius: 50%;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            min-width: 175px;
            height: 48px;

            border-radius: 12px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 20px;

            font-family: "DM Sans", sans-serif;
            font-size: 14px;
            font-weight: 600;

            text-decoration: none;

            transition:
                transform .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }

        .btn-primary {
            color: #fff;
            background: var(--app-primary);

            box-shadow:
                0 8px 20px rgba(109, 74, 255, 0.22);
        }

        .btn-primary:hover {
            background: var(--app-primary-dark);
            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(109, 74, 255, 0.28);
        }

        .btn-secondary {
            color: var(--app-primary);
            background: #fff;
            border: 1px solid #ded9f6;
        }

        .btn-secondary:hover {
            background: #faf9ff;
            transform: translateY(-2px);
        }

        .help-text {
            margin-top: 28px;
            color: var(--app-muted);
            font-size: 12px;
        }

        .help-text strong {
            color: var(--app-ink);
            font-weight: 600;
        }

        @keyframes popIn {
            0% {
                transform: scale(.6);
                opacity: 0;
            }

            70% {
                transform: scale(1.08);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 600px) {

            body {
                padding: 20px 14px;
            }

            .success-card {
                padding: 38px 22px 32px;
                border-radius: 21px;
            }

            .brand-name {
                font-size: 27px;
            }

            .success-title {
                font-size: 27px;
            }

            .success-message {
                font-size: 14px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="success-wrapper">

        <!-- Brand -->
        <div class="brand">
            <div class="brand-name">Himrishtey</div>
            <div class="brand-tagline">
                Find your perfect connection
            </div>
        </div>


        <!-- Success Card -->
        <div class="success-card">

            <!-- Success Icon -->
            <div class="success-icon-wrapper">
                <div class="success-icon">
                    ✓
                </div>
            </div>


            <!-- Heading -->
            <h1 class="success-title">
                Payment Successful!
            </h1>

            <p class="success-message">
                Thank you for upgrading your Himrishtey membership.
                Your payment has been successfully processed and your
                membership is now active.
            </p>


            <!-- Membership -->
            @if(session('plan'))

            <div class="membership-box">

                <div class="membership-status">
                    <span class="status-dot"></span>
                    Membership Active
                </div>

            </div>
            @endif


            <!-- Buttons -->
            <div class="actions">

                <a href="{{ route('home') }}"
                    class="btn btn-secondary">
                    Back to Home
                </a>

            </div>


            <div class="help-text">
                Your payment receipt and membership details have been recorded.
                <br>
                <strong>Thank you for choosing Himrishtey.</strong>
            </div>

        </div>

    </div>

</body>

</html>
