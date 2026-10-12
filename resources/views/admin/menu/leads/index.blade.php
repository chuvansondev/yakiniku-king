@extends('admin.layouts.app')

@section('title', __('Khách hàng tiềm năng'))
@section('page-title', __('Khách hàng tiềm năng'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\Lead::class, 'listRoute' => route('admin.menu.leads.index')])

@if(session('success'))
    <div class="admin-notice" role="status">{{ session('success') }}</div>
@endif

<div class="admin-page-heading">
    <h1>{{ __('Quản lý Lead') }}</h1>
    <a href="{{ route('admin.menu.leads.create') }}"
        style="background:#111; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;">
        {{ __('+ Thêm Lead') }}</a>

</div>


<div class="admin-table-wrap"><table
    border="1"
    cellpadding="10"
    cellspacing="0"
    width="100%"
>

    <thead>

        <tr>
            <th>ID</th>
            <th>{{ __('Họ tên') }}</th>
            <th>{{ __('SĐT') }}</th>
            <th>Email</th>
            <th>{{ __('Nội dung') }}</th>
            <th>{{ __('Trạng thái') }}</th>
            <th>{{ __('Ngày gửi') }}</th>
            <th>{{ __('Thao tác') }}</th>
        </tr>

    </thead>


    <tbody>

        @forelse($leads as $lead)

            <tr>

                <td>
                    {{ $lead->id }}
                </td>


                <td>
                    {{ $lead->name }}
                </td>


                <td>
                    {{ $lead->phone ?? '-' }}
                </td>


                <td>
                    {{ $lead->email ?? '-' }}
                </td>


                <td>
                    {{ $lead->message ?? '-' }}
                </td>


                <td>

                    @switch($lead->status)

                        @case(\App\Enums\LeadStatus::New->value)
                            {{ __('Mới') }}
                            @break

                        @case(\App\Enums\LeadStatus::Read->value)
                            {{ __('Đã xem') }}
                            @break

                        @case(\App\Enums\LeadStatus::Contacted->value)
                            {{ __('Đã liên hệ') }}
                            @break

                    @endswitch

                </td>


                <td>
                    {{ $lead->created_at->format('d/m/Y H:i') }}
                </td>


                <td>

                    <a href="{{ route('admin.menu.leads.edit', $lead) }}">
                        {{ __('Sửa') }}</a>


                    <form
                        action="{{ route('admin.menu.leads.destroy', $lead) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm(@json(__('Bạn có chắc muốn xóa lead này?')))"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            {{ __('Xóa') }}</button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="8">
                    {{ __('Chưa có lead nào.') }}</td>

            </tr>

        @endforelse

    </tbody>

</table></div>


@include('admin.menu.partials.pagination', ['paginator' => $leads])
@endsection
