@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa nhà hàng')
@section('page-title', 'Nhà hàng / Chỉnh sửa')

@section('content')

<h1>Chỉnh sửa nhà hàng</h1>

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
    action="{{ route('admin.menu.restaurants.update', $restaurant) }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @method('PUT')

    @include('admin.menu.restaurants._form', [
        'buttonText' => 'Cập nhật nhà hàng'
    ])

</form>

@endsection
