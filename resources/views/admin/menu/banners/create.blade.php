@extends('admin.layouts.app')

@section('title', __('Thêm Banner'))

@section('page-title', __('Thêm Banner'))

@section('content')

    <h1>{{ __('Thêm Banner') }}</h1>

    @if($errors->any())

        <div style="color:red; margin-bottom:20px;">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('admin.menu.banners.store') }}"
        method="POST"
        enctype="multipart/form-data"
        style="background:white; padding:25px;"
    >

        @csrf

        @include('admin.menu.banners._form')

        <button type="submit">
            {{ __('Lưu Banner') }}</button>

        <a href="{{ route('admin.menu.banners.index') }}">
            {{ __('Quay lại') }}</a>

    </form>

@endsection
