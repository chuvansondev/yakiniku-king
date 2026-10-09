@extends('frontend.layouts.app')

@section('title', __('Reset password'))

@section('content')
<section class="py-5 bg-light">
    <div class="container" style="max-width: 520px">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">{{ __('Reset password') }}</h1>
                <p class="text-muted mb-4">{{ __('Create a new password for your account.') }}</p>
                @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">{{ __('New password (at least 8 characters)') }}</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">{{ __('Confirm new password') }}</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">{{ __('Save new password') }}</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
