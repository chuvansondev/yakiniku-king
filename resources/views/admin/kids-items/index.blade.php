@extends('admin.layouts.app')

@section('title', __('Dành cho trẻ em'))

@section('page-title', __('Dành cho trẻ em'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\KidsItem::class, 'listRoute' => route('admin.kids-items.index')])
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>{{ __('Nội dung dành cho trẻ em') }}</h1>
        <a href="{{ route('admin.kids-items.create') }}" style="background:#111; color:white; padding:10px 15px; text-decoration:none;">
            {{ __('+ Thêm nội dung') }}</a>
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
                    <th>{{ __('Tên') }}</th>
                    <th>{{ __('Loại') }}</th>
                    <th>{{ __('Nhóm món ăn') }}</th>
                    <th>{{ __('Giá') }}</th>
                    <th>{{ __('Trạng thái') }}</th>
                    <th>{{ __('Thao tác') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $typeLabels[$item->type] ?? $item->type }}</td>
                        <td>{{ $item->food_category ? ($foodCategoryLabels[$item->food_category] ?? $item->food_category) : '—' }}</td>
                        <td>{{ $item->price !== null ? localized_price($item->price) : '—' }}</td>
                        <td>{{ $item->status ? __('Hiển thị') : __('Ẩn') }}</td>
                        <td>
                            <a href="{{ route('admin.kids-items.edit', $item) }}">{{ __('Sửa') }}</a>
                            <form action="{{ route('admin.kids-items.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm(@json(__('Bạn có chắc muốn xóa nội dung này?')))">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color:red; margin-left:10px;">{{ __('Xóa') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">{{ __('Chưa có nội dung dành cho trẻ em.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table></div>
    </div>

@include('admin.menu.partials.pagination', ['paginator' => $items])
@endsection
