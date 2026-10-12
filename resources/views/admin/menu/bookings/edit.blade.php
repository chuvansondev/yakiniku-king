@extends('admin.layouts.app')

@section('title', __('Chỉnh sửa đặt bàn'))
@section('page-title', __('Đặt bàn / Chỉnh sửa'))

@section('content')

<h1>{{ __('Chỉnh sửa booking') }}</h1>

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
    action="{{ route('admin.menu.bookings.update', $booking) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    @include('admin.menu.bookings._form', [
        'buttonText' => __('Cập nhật booking')
    ])

</form>

@endsection
