@extends('fontend.layouts.app')

@section('title', __('Create account'))

@section('content')
<section class="py-5 bg-light">
    <div class="container" style="max-width: 560px">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">{{ __('Create account') }}</h1>
                <p class="text-muted mb-4">{{ __('Create an account to use Yakiniku King.') }}</p>
                @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">{{ __('Full name') }}</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">{{ __('Password (at least 8 characters)') }}</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">{{ __('Confirm password') }}</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">{{ __('Create account') }}</button>
                </form>
                <p class="text-center small mt-4 mb-0">{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Sign in') }}</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
