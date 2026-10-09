@extends('frontend.layouts.app')

@section('title', __('Sign in'))

@section('content')
<section class="py-5 bg-light">
    <div class="container" style="max-width: 520px">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">{{ __('Sign in') }}</h1>
                <p class="text-muted mb-4">{{ __('Sign in to your Yakiniku King account.') }}</p>
                @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
                @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">{{ __('Password') }}</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                        <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">{{ __('Sign in') }}</button>
                </form>
                <div class="d-flex justify-content-between flex-wrap gap-2 mt-4 small">
                    <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    <a href="{{ route('register') }}">{{ __('Create account') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
