@extends('admin.layouts.app')

@section('title', 'Dành cho trẻ em')

@section('page-title', 'Dành cho trẻ em')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>Nội dung dành cho trẻ em</h1>
        <a href="{{ route('admin.kids-items.create') }}" style="background:#111; color:white; padding:10px 15px; text-decoration:none;">
            + Thêm nội dung
        </a>
    </div>

    @if (session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background:white; padding:20px; overflow-x:auto;">
        <div class="admin-table-wrap"><table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Loại</th>
                    <th>Nhóm món ăn</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $typeLabels[$item->type] ?? $item->type }}</td>
                        <td>{{ $item->food_category ? ($foodCategoryLabels[$item->food_category] ?? $item->food_category) : '—' }}</td>
                        <td>{{ $item->price !== null ? number_format($item->price, 0, ',', '.') . ' ₫' : '—' }}</td>
                        <td>{{ $item->status ? 'Hiển thị' : 'Ẩn' }}</td>
                        <td>
                            <a href="{{ route('admin.kids-items.edit', $item) }}">Sửa</a>
                            <form action="{{ route('admin.kids-items.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa nội dung này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color:red; margin-left:10px;">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">Chưa có nội dung dành cho trẻ em.</td>
                    </tr>
                @endforelse
            </tbody>
        </table></div>
    </div>
@endsection