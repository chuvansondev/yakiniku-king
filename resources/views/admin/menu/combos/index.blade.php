@extends('admin.layouts.app')

@section('title', __('Combo'))

@section('page-title', __('Combo'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\Combo::class, 'listRoute' => route('admin.menu.combos.index')])

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>{{ __('Combo') }}</h1>

        <a
            href="{{ route('admin.menu.combos.create') }}"
            style="
                background:#111;
                color:white;
                padding:10px 15px;
                text-decoration:none;
                border-radius:5px;
            "
        >
            {{ __('+ Thêm Combo') }}</a>

    </div>


    @if(session('success'))

        <div style="
            background:#d4edda;
            color:#155724;
            padding:12px;
            margin-bottom:20px;
            border-radius:5px;
        ">
            {{ session('success') }}
        </div>

    @endif


    <div style="
        background:white;
        padding:20px;
    ">

        <div class="admin-table-wrap"><table
            width="100%"
            cellpadding="10"
            cellspacing="0"
            border="1"
            style="border-collapse:collapse;"
        >

            <thead>

                <tr>

                    <th>ID</th>

                    <th>{{ __('Tên Combo') }}</th>

                    <th>{{ __('Giá') }}</th>

                    <th>{{ __('Giá gốc') }}</th>

                    <th>{{ __('Món trong Combo') }}</th>

                    <th>{{ __('Trạng thái') }}</th>

                    <th>{{ __('Thao tác') }}</th>

                </tr>

            </thead>

            <tbody>

                @forelse($combos as $combo)

                    <tr>

                        <td>
                            {{ $combo->id }}
                        </td>

                        <td>
                            {{ $combo->name }}
                        </td>

                        <td>
                            {{ localized_price($combo->price) }}
                        </td>

                        <td>

                            @if($combo->original_price)

                                {{ localized_price($combo->original_price) }}

                            @else

                                -

                            @endif

                        </td>

                        <td>

                            @forelse($combo->menuItems as $item)

                                <div>
                                    {{ $item->name }}
                                    ×
                                    {{ $item->pivot->quantity }}
                                </div>

                            @empty

                                <span>
                                    {{ __('Chưa có món') }}</span>

                            @endforelse

                        </td>

                        <td>

                            @if($combo->status)

                                <span style="color:green;">
                                    {{ __('Hiển thị') }}</span>

                            @else

                                <span style="color:red;">
                                    {{ __('Ẩn') }}</span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.menu.combos.edit', $combo) }}"
                            >
                                {{ __('Sửa') }}</a>

                            <form
                                action="{{ route('admin.menu.combos.destroy', $combo) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="
                                    return confirm(@json(__('Bạn có chắc muốn xóa Combo này?')))
                                "
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    style="
                                        color:red;
                                        margin-left:10px;
                                    "
                                >
                                    {{ __('Xóa') }}</button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            style="text-align:center;"
                        >
                            {{ __('Chưa có Combo nào.') }}</td>

                    </tr>

                @endforelse

            </tbody>

        </table></div>

    </div>


@include('admin.menu.partials.pagination', ['paginator' => $combos])
@endsection
