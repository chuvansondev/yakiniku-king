@extends('admin.layouts.app')

@section('title', 'Bí kíp ăn ngon')

@section('page-title', 'Bí kíp ăn ngon')

@section('content')

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>Danh sách bí kíp</h1>

        <a
            href="{{ route('admin.menu.tips.create') }}"
            style="background:#111; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;"
        >
            + Thêm bí kíp
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d1fae5; color:#065f46; padding:12px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background:white; padding:20px;">
        <div class="admin-table-wrap"><table width="100%" border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Slug</th>
                    <th>Ngày đăng</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @forelse($tips as $tip)
                    <tr>
                        <td>{{ $tip->id }}</td>
                        <td>
                            @if($tip->image)
                                <img
                                    src="{{ asset('storage/' . $tip->image) }}"
                                    alt="{{ $tip->title }}"
                                    style="width:180px; height:100px; object-fit:cover;"
                                >
                            @else
                                Không có ảnh
                            @endif
                        </td>
                        <td>{{ $tip->title }}</td>
                        <td>{{ $tip->slug }}</td>
                        <td>
                            @if($tip->published_at)
                                {{ $tip->published_at->format('d/m/Y H:i') }}
                            @else
                                Chưa đăng
                            @endif
                        </td>
                        <td>
                            @if($tip->status)
                                <span style="color:green;">Đang hiển thị</span>
                            @else
                                <span style="color:red;">Đang ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.menu.tips.edit', $tip) }}">Sửa</a>

                            <form
                                action="{{ route('admin.menu.tips.destroy', $tip) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Bạn có chắc muốn xóa bí kíp này?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">Chưa có bí kíp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table></div>
    </div>

@endsection
