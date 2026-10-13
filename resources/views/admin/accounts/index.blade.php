@extends('admin.layouts.app')

@section('title', __('accounts.title'))
@section('page-title', __('accounts.title'))

@section('content')
<div class="admin-accounts">
    <div class="admin-page-heading">
        <div>
            <p class="admin-accounts-eyebrow">YAKINIKU KING · {{ __('accounts.admin') }}</p>
            <h1>{{ __('accounts.title') }}</h1>
            <p>{{ __('accounts.description') }}</p>
        </div>
        <a class="admin-accounts-primary" href="{{ route('admin.accounts.create') }}"><span aria-hidden="true">＋</span> {{ __('accounts.create') }}</a>
    </div>

    @if (session('success'))<div class="admin-accounts-notice" role="status">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="admin-accounts-errors" role="alert">{{ $errors->first() }}</div>@endif

    <div class="admin-accounts-toolbar">
        <div><strong>{{ number_format($accounts->total()) }}</strong> {{ __('accounts.count') }}</div>
        <form method="GET" action="{{ route('admin.accounts.index') }}" class="admin-accounts-filters">
            <label class="admin-accounts-search"><span aria-hidden="true">⌕</span><input type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('accounts.search') }}"><button type="submit">{{ __('accounts.search_button') }}</button></label>
            <select name="status" aria-label="{{ __('accounts.filter_status') }}" onchange="this.form.requestSubmit()">
                <option value="">{{ __('accounts.all_statuses') }}</option>
                <option value="active" @selected(request('status') === 'active')>{{ __('accounts.active') }}</option>
                <option value="locked" @selected(request('status') === 'locked')>{{ __('accounts.locked') }}</option>
            </select>
        </form>
    </div>

    <div class="admin-accounts-table-wrap"><table class="admin-accounts-table">
        <thead><tr><th>{{ __('accounts.title') }}</th><th>{{ __('accounts.role') }}</th><th>{{ __('accounts.status') }}</th><th>{{ __('accounts.created_at') }}</th><th class="admin-accounts-actions-heading">{{ __('accounts.actions') }}</th></tr></thead>
        <tbody>
        @forelse ($accounts as $account)
            <tr>
                <td><div class="admin-account-person"><span class="admin-account-avatar">{{ mb_strtoupper(mb_substr($account->name, 0, 1)) }}</span><span><strong>{{ $account->name }}</strong><small>{{ $account->email }}</small></span></div></td>
                <td><span class="admin-account-role">{{ $account->role?->name ?? __('accounts.customer') }}</span></td>
                <td><span class="admin-account-status {{ $account->is_locked ? 'is-locked' : 'is-active' }}"><i></i>{{ $account->is_locked ? __('accounts.locked') : __('accounts.active') }}</span></td>
                <td class="admin-account-date">{{ $account->created_at?->format('d/m/Y') }}</td>
                <td><div class="admin-account-row-actions"><a href="{{ route('admin.accounts.edit', $account) }}">{{ __('accounts.edit_action') }}</a>
                    @if (!$account->is(auth()->user()))
                    <form method="POST" action="{{ route('admin.accounts.lock', $account) }}" onsubmit="return confirm(@json($account->is_locked ? __('accounts.unlock_confirm') : __('accounts.lock_confirm')))" >@csrf @method('PATCH')<button class="{{ $account->is_locked ? 'is-unlock' : 'is-lock' }}" type="submit">{{ $account->is_locked ? __('accounts.unlock') : __('accounts.lock') }}</button></form>
                    @endif
                </div></td>
            </tr>
        @empty
            <tr><td colspan="5"><div class="admin-accounts-empty"><span aria-hidden="true">◎</span><strong>{{ __('accounts.empty_title') }}</strong><p>{{ __('accounts.empty_description') }}</p></div></td></tr>
        @endforelse
        </tbody>
    </table></div>

    <div class="admin-accounts-pagination">{{ $accounts->links() }}</div>
</div>
@endsection
