@extends('admin.layouts.app')

@section('title', 'Combo')

@section('page-title', 'Combo')

@section('content')

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Combo</h1>

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
            + Thêm Combo
        </a>

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

                    <th>Tên Combo</th>

                    <th>Giá</th>

                    <th>Giá gốc</th>

                    <th>Món trong Combo</th>

                    <th>Trạng thái</th>

                    <th>Thao tác</th>

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
                            {{ number_format($combo->price, 0, ',', '.') }} ₫
                        </td>

                        <td>

                            @if($combo->original_price)

                                {{ number_format($combo->original_price, 0, ',', '.') }} ₫

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
                                    Chưa có món
                                </span>

                            @endforelse

                        </td>

                        <td>

                            @if($combo->status)

                                <span style="color:green;">
                                    Hiển thị
                                </span>

                            @else

                                <span style="color:red;">
                                    Ẩn
                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.menu.combos.edit', $combo) }}"
                            >
                                Sửa
                            </a>

                            <form
                                action="{{ route('admin.menu.combos.destroy', $combo) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="
                                    return confirm('Bạn có chắc muốn xóa Combo này?')
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
                                    Xóa
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            style="text-align:center;"
                        >
                            Chưa có Combo nào.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table></div>

    </div>

@endsection