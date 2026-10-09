<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Admin sign in') }} - Yakiniku King</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        :root {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            color: #202923;
            background: #f1f4f1;
        }

        body {
            min-width: 320px;
            min-height: 100vh;
            margin: 0;
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
        }

        .login-shell {
            display: grid;
            min-height: 100vh;
            grid-template-columns: minmax(0, 1.05fr) minmax(400px, .95fr);
        }

        .login-visual {
            position: relative;
            display: flex;
            min-height: 100vh;
            align-items: flex-end;
            padding: clamp(30px, 6vw, 84px);
            overflow: hidden;
            background: #243229 url('{{ asset('yakiniku-king/logo3.png') }}') center / cover no-repeat;
            color: #fff;
        }

        .login-visual::before {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgb(20 29 23 / 12%), rgb(20 29 23 / 78%));
            content: '';
        }

        .login-brand {
            position: absolute;
            z-index: 1;
            top: clamp(30px, 5vw, 64px);
            left: clamp(30px, 6vw, 84px);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .login-brand-mark {
            display: grid;
            width: 44px;
            height: 44px;
            place-items: center;
            border: 1px solid rgb(255 255 255 / 55%);
            color: #ff0000;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 18px;
        }

        .login-brand-name {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .1em;
        }

        .login-visual-copy {
            position: relative;
            z-index: 1;
            max-width: 510px;
        }

        .login-visual-copy p {
            margin: 0 0 12px;
            color: #ff0000;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .login-visual-copy h1 {
            margin: 0;
            color: #fff;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: clamp(38px, 5vw, 66px);
            font-weight: 400;
            line-height: 1.04;
        }

        .login-panel {
            display: grid;
            min-height: 100vh;
            align-content: center;
            justify-items: center;
            padding: 44px 28px;
            background: #f1f4f1;
        }

        .login-form-wrap {
            width: min(100%, 390px);
        }

        .login-kicker {
            margin: 0 0 10px;
            color: #527761;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        .login-form-wrap h2 {
            margin: 0 0 8px;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 34px;
            font-weight: 400;
        }

        .login-subtitle {
            margin: 0 0 30px;
            color: #737d76;
            font-size: 13px;
        }

        .login-error {
            margin-bottom: 20px;
            padding: 12px 14px;
            border-left: 3px solid #c84b3b;
            background: #f9e9e6;
            color: #8e332b;
            font-size: 12px;
        }

        .login-form-group {
            margin-bottom: 19px;
        }

        .login-form-group label {
            display: block;
            margin-bottom: 7px;
            color: #37443b;
            font-size: 12px;
            font-weight: 700;
        }

        .login-form-group input {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid #ccd5ce;
            border-radius: 4px;
            background: #fff;
            color: #202923;
            font: inherit;
            font-size: 13px;
        }

        .login-form-group input:focus {
            border-color: #70917b;
            outline: 3px solid rgb(82 119 97 / 14%);
        }

        .login-submit {
            width: 100%;
            min-height: 46px;
            margin-top: 4px;
            border: 1px solid #aa3d31;
            border-radius: 4px;
            background: #c84b3b;
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            transition: background-color 140ms ease;
        }

        .login-submit:hover {
            background: #aa3d31;
        }

        .login-submit:focus-visible {
            outline: 3px solid rgb(200 75 59 / 30%);
            outline-offset: 3px;
        }

        @media (max-width: 760px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-visual {
                min-height: 270px;
                padding: 28px;
            }

            .login-brand {
                top: 24px;
                left: 28px;
            }

            .login-visual-copy h1 {
                font-size: 42px;
            }

            .login-panel {
                min-height: auto;
                padding: 42px 24px 54px;
            }
        }

        @media (max-width: 420px) {
            .login-visual {
                min-height: 230px;
            }

            .login-visual-copy h1 {
                font-size: 36px;
            }

            .login-form-wrap h2 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>
    <main class="login-shell">
        <section class="login-visual" aria-label="Yakiniku King">
            <div class="login-brand">
                <span class="login-brand-mark" aria-hidden="true">YK</span>
                <span class="login-brand-name">YAKINIKU KING</span>
            </div>
            <div class="login-visual-copy">
                <p>Yakiniku King</p>
                <h1>{{ __('Restaurant administration') }}</h1>
            </div>
        </section>

        <section class="login-panel" aria-labelledby="login-title">
            <div class="login-form-wrap">
                <p class="login-kicker">{{ __('Administration') }}</p>
                <h2 id="login-title">{{ __('Admin sign in') }}</h2>
                <p class="login-subtitle">Yakiniku King · Admin</p>

                @if ($errors->any())
                    <div class="login-error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="login-form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="{{ __('Enter your email') }}"
                            autocomplete="username"
                            required
                        >
                    </div>

                    <div class="login-form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="{{ __('Enter your password') }}"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-submit">{{ __('Sign in') }}</button>
                    <p class="mt-3 small text-center"><a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></p>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
