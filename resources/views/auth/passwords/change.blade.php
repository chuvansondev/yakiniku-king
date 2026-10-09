@extends('frontend.layouts.app')

@section('title', __('Change password'))

@section('content')
<section class="py-5 bg-light">
    <div class="container" style="max-width: 520px">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">{{ __('Change password') }}</h1>
                <p class="text-muted mb-4">{{ __('Confirm your current password before setting a new one.') }}</p>
                @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
                @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
                <form method="POST" action="{{ route('password.change.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" for="current_password">{{ __('Current password') }}</label>
                        <input class="form-control" id="current_password" name="current_password" type="password" autocomplete="current-password" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">{{ __('New password (at least 8 characters)') }}</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">{{ __('Confirm new password') }}</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">{{ __('Change password') }}</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
