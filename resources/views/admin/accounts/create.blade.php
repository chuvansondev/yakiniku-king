@extends('admin.layouts.app')
@section('title', __('accounts.create'))
@section('page-title', __('accounts.create'))
@section('content')
<div class="admin-accounts admin-account-editor">
    <a class="admin-accounts-back" href="{{ route('admin.accounts.index') }}">← {{ __('accounts.back') }}</a>
    <div class="admin-account-editor-heading"><span class="admin-accounts-eyebrow">YAKINIKU KING · {{ __('accounts.admin') }}</span><h1>{{ __('accounts.create_new') }}</h1><p>{{ __('accounts.create_description') }}</p></div>
    <form method="POST" action="{{ route('admin.accounts.store') }}">@csrf @include('admin.accounts._form')</form>
</div>
@endsection
