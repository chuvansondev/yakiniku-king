@extends('admin.layouts.app')

@section('title', __('Sửa món'))

@section('page-title', __('Sửa món'))

@section('content')

    <h1>{{ __('Sửa món ăn') }}</h1>

    @if($errors->any())

        <div style="color:red;">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('admin.menu.items.update', $item) }}"
        method="POST"
        enctype="multipart/form-data"
        style="background:white; padding:25px;"
    >

        @csrf

        @method('PUT')


        <div style="margin-bottom:20px;">

            <label>{{ __('Danh mục') }}</label>

            <br>

            <select
                name="category_id"
                style="width:100%; padding:10px;"
                required
            >

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>

        </div>

        @include('admin.menu.partials.english-field', ['name' => 'name_en', 'label' => __('Tên món'), 'value' => $item->name_en ?? '', 'margin' => '20px'])


        <div style="margin-bottom:20px;">

            <label>{{ __('Tên món') }}</label>

            <br>

            <input
                type="text"
                name="name"
                value="{{ old('name', $item->name) }}"
                style="width:100%; padding:10px;"
                required
            >

        </div>


        <div style="margin-bottom:20px;">

            <label>Slug</label>

            <br>

            <input
                type="text"
                name="slug"
                value="{{ old('slug', $item->slug) }}"
                style="width:100%; padding:10px;"
            >

        </div>


        <div style="margin-bottom:20px;">

            <label>{{ __('Mô tả') }}</label>

            <br>

            <textarea
                name="description"
                rows="5"
                style="width:100%; padding:10px;"
            >{{ old('description', $item->description) }}</textarea>

        </div>

        @include('admin.menu.partials.english-field', ['name' => 'description_en', 'label' => __('Mô tả'), 'value' => $item->description_en ?? '', 'type' => 'textarea', 'rows' => 5, 'margin' => '20px'])


        <div data-image-field style="margin-bottom:20px;">

            <label>{{ __('Hình ảnh') }}</label>

            <br>

            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp"
                style="width:100%; padding:10px;"
            >

            @if($item->image)
                <input type="hidden" name="remove_image" value="0" data-image-remove-value>
                <div data-current-image-preview>
                    <p>{{ __('Ảnh hiện tại:') }}</p>
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="max-width:240px;">
                    <p>{{ __('Để trống nếu muốn giữ ảnh hiện tại.') }}</p>
                </div>
                <button class="admin-image-remove-button" type="button" data-image-remove-toggle aria-pressed="false">{{ __('Xóa ảnh hiện tại') }}</button>
            @endif

        </div>


        <div style="margin-bottom:20px;">

            <label>{{ __('Giá') }}</label>

            <br>

            <input
                type="number"
                name="price"
                value="{{ old('price', $item->price) }}"
                min="0"
                step="0.01"
                style="width:100%; padding:10px;"
                required
            >

        </div>


        <div style="margin-bottom:20px;">

            <label>{{ __('Thứ tự') }}</label>

            <br>

            <input
                type="number"
                name="sort_order"
                value="{{ old('sort_order', $item->sort_order) }}"
                min="0"
                style="width:100%; padding:10px;"
            >

        </div>


        <div style="margin-bottom:15px;">

            <label>

                <input
                    type="checkbox"
                    name="is_must_try"
                    value="1"
                    {{ $item->is_must_try ? 'checked' : '' }}
                >

                Must Try

            </label>

        </div>


        <div style="margin-bottom:20px;">

            <label>

                <input
                    type="checkbox"
                    name="status"
                    value="1"
                    {{ $item->status ? 'checked' : '' }}
                >

                {{ __('Hiển thị') }}</label>

        </div>


        <button
            type="submit"
            style="
                background:#111;
                color:white;
                padding:10px 20px;
                border:none;
                border-radius:5px;
            "
        >
            {{ __('Cập nhật') }}</button>

        <a
            href="{{ route('admin.menu.items.index') }}"
            style="margin-left:10px;"
        >
            {{ __('Quay lại') }}</a>

    </form>

@endsection