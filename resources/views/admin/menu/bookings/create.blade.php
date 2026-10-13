@extends('admin.layouts.app')

@section('title', __('Thêm đặt bàn'))
@section('page-title', __('Đặt bàn / Thêm mới'))

@section('content')
<h1>{{ __('Thêm đặt bàn') }}</h1>

@if ($errors->any())
    <div class="restaurant-form-errors" role="alert">
        <strong>{{ __('Vui lòng kiểm tra lại thông tin đặt bàn.') }}</strong>
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form class="admin-entity-form admin-booking-form" action="{{ route('admin.menu.bookings.store') }}" method="POST">
    @csrf
    @include('admin.menu.bookings._form', ['buttonText' => __('Thêm đặt bàn')])
</form>
@endsection
