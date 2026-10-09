<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Admin sign in') }} - Yakiniku King</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        :root {
            font-family: Arial, 'Segoe UI', sans-serif;
            color: #202923;
            background: #f1f4f1;
        }

        body {
            min-width: 320px;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, 'Segoe UI', sans-serif;
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
            color: #e7b983;
            font-family: Arial, 'Segoe UI', sans-serif;
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
            color: #c84b3b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .login-visual-copy h1 {
            margin: 0;
            color: #fff;
            font-family: Arial, 'Segoe UI', sans-serif;
            font-size: clamp(38px, 5vw, 66px);
            font-weight: 700;
            line-height: 1.04;
        }

        .login-panel {
            display: grid;
            min-height: 100vh;
            align-content: center;
            justify-items: center;
            padding: 44px 28px;
            background:
                radial-gradient(circle at 100% 0%, rgb(200 75 59 / 7%), transparent 35%),
                #f1f4f1;
        }

        .login-form-wrap {
            width: min(100%, 390px);
            padding: 38px;
            border: 1px solid #e1e7e2;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 20px 55px rgb(32 41 35 / 8%);
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
            font-family: Arial, 'Segoe UI', sans-serif;
            font-size: 34px;
            font-weight: 700;
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
            border-radius: 9px;
            background: #fff;
            color: #202923;
            font: inherit;
            font-size: 13px;
        }

        .login-form-group input:focus {
            border-color: #70917b;
            outline: 0;
            box-shadow: 0 0 0 3px rgb(82 119 97 / 14%);
        }

        .login-submit {
            width: 100%;
            min-height: 46px;
            margin-top: 4px;
            border: 1px solid #aa3d31;
            border-radius: 9px;
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

        .login-form-wrap a {
            color: #a93c31;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            text-underline-offset: 3px;
        }

        .login-form-wrap a:hover {
            color: #812f28;
            text-decoration: underline;
        }

        .login-form-wrap a:focus-visible {
            border-radius: 3px;
            outline: 3px solid rgb(200 75 59 / 24%);
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

            .login-form-wrap {
                padding: 30px 24px;
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
