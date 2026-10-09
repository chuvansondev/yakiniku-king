@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $title)

@section('content')
    <h1>{{ $title }}</h1>

    @include('admin.menu.partials.validation-errors')

    <form class="admin-resource-form" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        @include($fieldsComponent, $fieldsData)

        <button type="submit">{{ $submitLabel }}</button>
        <a href="{{ $backUrl }}">Quay lại</a>
    </form>
@endsection
