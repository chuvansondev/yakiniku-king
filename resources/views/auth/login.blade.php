@extends('fontend.layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<section class="py-5 bg-light">
    <div class="container" style="max-width: 520px">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">Đăng nhập</h1>
                <p class="text-muted mb-4">Đăng nhập vào tài khoản Yakiniku King của bạn.</p>
                @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
                @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">Đăng nhập</button>
                </form>
                <div class="d-flex justify-content-between flex-wrap gap-2 mt-4 small">
                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    <a href="{{ route('register') }}">Tạo tài khoản</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
