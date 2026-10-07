@extends('fontend.layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<section class="py-5 bg-light">
    <div class="container" style="max-width: 520px">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">Quên mật khẩu</h1>
                <p class="text-muted mb-4">Nhập email đã đăng ký để nhận liên kết đặt lại mật khẩu.</p>
                @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
                @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">Gửi liên kết đặt lại</button>
                </form>
                <p class="text-center small mt-4 mb-0"><a href="{{ route('login') }}">Quay lại đăng nhập</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
