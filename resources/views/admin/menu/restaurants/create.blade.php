@extends('admin.layouts.app')

@section('title', 'Thêm nhà hàng')
@section('page-title', 'Nhà hàng / Thêm mới')

@section('content')

<h1>Thêm nhà hàng</h1>

@if ($errors->any())
    <div>
        <strong>Có lỗi xảy ra:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form class="admin-entity-form"
    action="{{ route('admin.menu.restaurants.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf

    @include('admin.menu.restaurants._form', [
        'buttonText' => 'Thêm nhà hàng'
    ])

</form>

@endsection
