@extends('admin.layouts.app')

@section('title', __('Sửa Banner'))

@section('page-title', __('Sửa Banner'))

@section('content')

<h1>{{ __('Sửa Banner') }}</h1>


@if($errors->any())

    <div style="color:red; margin-bottom:20px;">

        <ul>

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<form
    action="{{ route('admin.menu.banners.update', $banner) }}"
    method="POST"
    enctype="multipart/form-data"
    style="
        background:white;
        padding:25px;
    "
>

    @csrf

    @method('PUT')


    @include('admin.menu.banners._form')


    <button type="submit">
        {{ __('Cập nhật Banner') }}</button>


    <a href="{{ route('admin.menu.banners.index') }}">
        {{ __('Quay lại') }}</a>

</form>

@endsection