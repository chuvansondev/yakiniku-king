@extends('admin.layouts.app')

@section('title', 'Danh mục Menu')

@section('page-title', 'Danh mục Menu')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">

        <h1>Danh mục Menu</h1>

        <a
            href="{{ route('admin.menu.categories.create') }}"
            style="
                background: #111;
                color: white;
                padding: 10px 15px;
                text-decoration: none;
                border-radius: 5px;
            "
        >
            + Thêm danh mục
        </a>

    </div>


    {{-- Thông báo thành công --}}

    @if(session('success'))

        <div
            style="
                background: #d4edda;
                color: #155724;
                padding: 12px;
                margin-bottom: 20px;
                border-radius: 5px;
            "
        >
            {{ session('success') }}
        </div>

    @endif


    {{-- Bảng category --}}

    <div style="background: white; padding: 20px; border-radius: 8px;">

        <div class="admin-table-wrap"><table
            width="100%"
            cellpadding="10"
            cellspacing="0"
            border="1"
            style="border-collapse: collapse;"
        >

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Slug</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>

            </thead>

            <tbody>

                @forelse($categories as $category)

                    <tr>

                        <td>
                            {{ $category->id }}
                        </td>

                        <td>
                            {{ $category->name }}
                        </td>

                        <td>
                            {{ $category->slug }}
                        </td>

                        <td>
                            {{ $category->sort_order }}
                        </td>

                        <td>

                            @if($category->status)

                                <span style="color: green;">
                                    Đang hiển thị
                                </span>

                            @else

                                <span style="color: red;">
                                    Đã ẩn
                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.menu.categories.edit', $category) }}"
                            >
                                Sửa
                            </a>


                            <form
                                action="{{ route('admin.menu.categories.destroy', $category) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    style="color: red; margin-left: 10px;"
                                >
                                    Xóa
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            style="text-align: center;"
                        >
                            Chưa có danh mục nào.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table></div>

    </div>

@endsection