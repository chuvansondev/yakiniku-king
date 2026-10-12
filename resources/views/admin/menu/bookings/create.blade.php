@extends('admin.layouts.app')

@section('title', __('Thêm đặt bàn'))
@section('page-title', __('Đặt bàn / Thêm mới'))

@section('content')

<h1>{{ __('Thêm booking') }}</h1>

@if ($errors->any())

    <div>
        <strong>{{ __('Có lỗi xảy ra:') }}</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

@endif


<form class="admin-entity-form"
    action="{{ route('admin.menu.bookings.store') }}"
    method="POST"
>
    @csrf

    @include('admin.menu.bookings._form', [
        'buttonText' => __('Thêm booking')
    ])

</form>

@endsection
