@extends('admin.layouts.app')

@section('title', __('Thêm nội dung trẻ em'))

@section('page-title', __('Thêm nội dung trẻ em'))

@section('content')
    <h1>{{ __('Thêm nội dung dành cho trẻ em') }}</h1>

    @if ($errors->any())
        <div style="color:red; margin-bottom:16px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kids-items.store') }}" method="POST" enctype="multipart/form-data" style="background:white; padding:25px;">
        @csrf

        <div style="margin-bottom:20px;">
            <label for="name">{{ __('Tên') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required style="display:block; width:100%; padding:10px;">
        </div>

        @include('admin.menu.partials.english-field', ['name' => 'name_en', 'label' => __('Tên'), 'value' => old('name_en', '')])

        <div style="margin-bottom:20px;">
            <label for="slug">Slug</label>
            <input id="slug" type="text" name="slug" value="{{ old('slug') }}" placeholder="{{ __('admin_lists.slug_auto') }}" style="display:block; width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:20px;">
            <label for="type">{{ __('Loại nội dung') }}</label>
            <select id="type" name="type" required style="display:block; width:100%; padding:10px;">
                @foreach ($typeLabels as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', \App\Enums\KidsItemType::Food->value) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:20px;">
            <label for="food_category">{{ __('Nhóm món ăn') }}</label>
            <select id="food_category" name="food_category" style="display:block; width:100%; padding:10px;">
                <option value="">{{ __('Không áp dụng') }}</option>
                @foreach ($foodCategoryLabels as $value => $label)
                    <option value="{{ $value }}" @selected(old('food_category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:20px;">
            <label for="description">{{ __('Mô tả') }}</label>
            <textarea id="description" name="description" rows="5" style="display:block; width:100%; padding:10px;">{{ old('description') }}</textarea>
        </div>

        @include('admin.menu.partials.english-field', ['name' => 'description_en', 'label' => __('Mô tả'), 'value' => old('description_en', ''), 'type' => 'textarea', 'rows' => 5])

        <div style="margin-bottom:20px;">
            <label for="image">{{ __('Hình ảnh') }}</label>
            <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp" style="display:block; width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:20px;">
            <label for="price">{{ __('Giá (không bắt buộc)') }}</label>
            <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" style="display:block; width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:20px;">
            <label for="sort_order">{{ __('Thứ tự') }}</label>
            <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="display:block; width:100%; padding:10px;">
        </div>

        <label style="margin-bottom:20px;">
            <input type="checkbox" name="status" value="1" @checked(old('status', '1') === '1')>
            {{ __('Hiển thị') }}</label>

        <div>
            <button type="submit">{{ __('Lưu') }}</button>
            <a href="{{ route('admin.kids-items.index') }}" style="margin-left:10px;">{{ __('Quay lại') }}</a>
        </div>
    </form>
@endsection
