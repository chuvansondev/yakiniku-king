@extends('admin.layouts.app')

@section('content')

@if(session('success'))

    <div style="margin-bottom: 15px;">
        {{ session('success') }}
    </div>

@endif


<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Quản lý Lead</h1>
    <a href="{{ route('admin.menu.leads.create') }}"
        style="background:#111; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;">
        + Thêm Lead
    </a>

</div>


<table
    border="1"
    cellpadding="10"
    cellspacing="0"
    width="100%"
>

    <thead>

        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>SĐT</th>
            <th>Email</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th>Ngày gửi</th>
            <th>Thao tác</th>
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
                            Mới
                            @break

                        @case(\App\Enums\LeadStatus::Read->value)
                            Đã xem
                            @break

                        @case(\App\Enums\LeadStatus::Contacted->value)
                            Đã liên hệ
                            @break

                    @endswitch

                </td>


                <td>
                    {{ $lead->created_at->format('d/m/Y H:i') }}
                </td>


                <td>

                    <a href="{{ route('admin.menu.leads.edit', $lead) }}">
                        Sửa
                    </a>


                    <form
                        action="{{ route('admin.menu.leads.destroy', $lead) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Bạn có chắc muốn xóa lead này?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            Xóa
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="8">
                    Chưa có lead nào.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>

@endsection
