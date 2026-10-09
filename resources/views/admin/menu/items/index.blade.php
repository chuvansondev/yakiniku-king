@extends('admin.layouts.app')

@section('title', 'Menu Items')

@section('page-title', 'Menu Items')

@section('content')

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

        <h1>Món ăn</h1>

        <a
            href="{{ route('admin.menu.items.create') }}"
            style="
                background:#111;
                color:white;
                padding:10px 15px;
                text-decoration:none;
                border-radius:5px;
            "
        >
            + Thêm món
        </a>

    </div>


    @if(session('success'))

        <div
            style="
                background:#d4edda;
                color:#155724;
                padding:12px;
                margin-bottom:20px;
            "
        >
            {{ session('success') }}
        </div>

    @endif


    <div style="background:white; padding:20px;">

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
                    <th>Tên món</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Must Try</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>

            </thead>

            <tbody>

                @forelse($items as $item)

                    <tr>

                        <td>
                            {{ $item->id }}
                        </td>

                        <td>
                            {{ $item->name }}
                        </td>

                        <td>
                            {{ $item->category->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ number_format($item->price, 0, ',', '.') }} ₫
                        </td>

                        <td>
                            {{ $item->is_must_try ? 'Có' : 'Không' }}
                        </td>

                        <td>

                            @if($item->status)

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
                                href="{{ route('admin.menu.items.edit', $item) }}"
                            >
                                Sửa
                            </a>

                            <form
                                action="{{ route('admin.menu.items.destroy', $item) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Bạn có chắc muốn xóa món này?')"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    style="color:red; margin-left:10px;"
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
                            Chưa có món ăn nào.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table></div>

    </div>

@endsection