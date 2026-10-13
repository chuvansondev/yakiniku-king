@extends('admin.layouts.app')

@section('title', __('Thêm nhà hàng'))
@section('page-title', __('Nhà hàng / Thêm mới'))

@section('content')
<h1>{{ __('Thêm nhà hàng') }}</h1>

@if ($errors->any())
    <div class="restaurant-form-errors" role="alert">
        <strong>{{ __('Có lỗi xảy ra:') }}</strong>
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form class="admin-entity-form restaurant-form" action="{{ route('admin.menu.restaurants.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.menu.restaurants._form', ['buttonText' => __('Thêm nhà hàng')])
</form>
@endsection
