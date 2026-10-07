@extends('fontend.layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
<section class="py-5 bg-light">
    <div class="container" style="max-width: 520px">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">Đổi mật khẩu</h1>
                <p class="text-muted mb-4">Xác nhận mật khẩu hiện tại trước khi đặt mật khẩu mới.</p>
                @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
                @if ($errors->any()) <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div> @endif
                <form method="POST" action="{{ route('password.change.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
                        <input class="form-control" id="current_password" name="current_password" type="password" autocomplete="current-password" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Mật khẩu mới (ít nhất 8 ký tự)</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">Nhập lại mật khẩu mới</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
