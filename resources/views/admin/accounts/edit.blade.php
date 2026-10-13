@extends('admin.layouts.app')
@section('title', __('accounts.edit'))
@section('page-title', __('accounts.edit'))
@section('content')
<div class="admin-accounts admin-account-editor">
    <a class="admin-accounts-back" href="{{ route('admin.accounts.index') }}">← {{ __('accounts.back') }}</a>
    <div class="admin-account-editor-heading"><span class="admin-accounts-eyebrow">YAKINIKU KING · {{ __('accounts.admin') }}</span><h1>{{ __('accounts.edit') }}</h1><p>{{ __('accounts.edit_description', ['name' => $account->name]) }}</p></div>
    <form method="POST" action="{{ route('admin.accounts.update', $account) }}">@csrf @method('PUT') @include('admin.accounts._form', ['account' => $account])</form>
</div>
@endsection
