@extends('admin.layouts.app')

@section('title', __('Thêm khách hàng tiềm năng'))
@section('page-title', __('Khách hàng tiềm năng / Thêm mới'))

@section('content')

<h1>{{ __('Thêm Lead') }}</h1>

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
    action="{{ route('admin.menu.leads.store') }}"
    method="POST"
>
    @csrf

    @include('admin.menu.leads._form', [
        'buttonText' => __('Thêm Lead')
    ])

</form>

@endsection
