@extends('admin.layouts.app')

@section('title', __('Chỉnh sửa khách hàng tiềm năng'))
@section('page-title', __('Khách hàng tiềm năng / Chỉnh sửa'))

@section('content')

<h1>{{ __('Chỉnh sửa Lead') }}</h1>

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
    action="{{ route('admin.menu.leads.update', $lead) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    @include('admin.menu.leads._form', [
        'buttonText' => __('Cập nhật Lead')
    ])

</form>

@endsection
