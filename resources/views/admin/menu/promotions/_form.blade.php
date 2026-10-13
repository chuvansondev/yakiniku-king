<div style="margin-bottom:15px;">

    <label>
        {{ __('Tiêu đề') }}</label>

    <br>

    <input
        type="text"
        name="title"
        value="{{ old('title', $promotion->title ?? '') }}"
        style="
            width:100%;
            padding:10px;
        "
    >

</div>

@include('admin.menu.partials.english-field', [
    'name' => 'title_en',
    'label' => __('Tiêu đề'),
    'value' => $promotion->title_en ?? '',
])


<div style="margin-bottom:15px;">

    <label>
        Slug
    </label>

    <br>

    <input
        type="text"
        name="slug"
        value="{{ old('slug', $promotion->slug ?? '') }}"
        placeholder="{{ __('admin_lists.slug_auto') }}"
        style="
            width:100%;
            padding:10px;
        "
    >

</div>

@include('admin.menu.partials.english-field', [
    'name' => 'short_description_en',
    'label' => __('Mô tả ngắn'),
    'value' => $promotion->short_description_en ?? '',
    'type' => 'textarea',
    'rows' => 4,
])


<div style="margin-bottom:15px;">

    <label>
        {{ __('Mô tả ngắn') }}</label>

    <br>

    <textarea
        name="short_description"
        rows="4"
        style="
            width:100%;
            padding:10px;
        "
    >{{ old('short_description', $promotion->short_description ?? '') }}</textarea>

</div>


<div style="margin-bottom:15px;">

    <label>
        {{ __('Nội dung chi tiết') }}</label>

    <br>

    <textarea
        name="description"
        rows="10"
        style="
            width:100%;
            padding:10px;
        "
    >{{ old('description', $promotion->description ?? '') }}</textarea>

</div>

@include('admin.menu.partials.english-field', [
    'name' => 'description_en',
    'label' => __('Nội dung chi tiết'),
    'value' => $promotion->description_en ?? '',
    'type' => 'textarea',
    'rows' => 10,
])


<div data-image-field style="margin-bottom:15px;">

    <label>
        {{ __('Hình ảnh') }}</label>

    <br>

    <input
        type="file"
        name="image"
        accept=".jpg,.jpeg,.png,.webp"
    >


    @if(isset($promotion) && $promotion->image)

        <input type="hidden" name="remove_image" value="0" data-image-remove-value>
        <div data-current-image-preview style="margin-top:10px;">

            <p>{{ __('Ảnh hiện tại:') }}</p>

            <img
                src="{{ asset('storage/' . $promotion->image) }}"
                alt="{{ $promotion->title }}"
                style="
                    width:300px;
                    max-height:200px;
                    object-fit:cover;
                "
            >

        </div>

        <button class="admin-image-remove-button" type="button" data-image-remove-toggle aria-pressed="false">{{ __('Xóa ảnh hiện tại') }}</button>

    @endif

</div>


<div
    style="
        display:flex;
        gap:20px;
        margin-bottom:15px;
    "
>

    <div style="flex:1;">

        <label>
            {{ __('Ngày bắt đầu') }}</label>

        <br>

        <input
            type="date"
            name="start_date"
            value="{{ old(
                'start_date',
                isset($promotion) && $promotion->start_date
                    ? $promotion->start_date->format('Y-m-d')
                    : ''
            ) }}"
            style="
                width:100%;
                padding:10px;
            "
        >

    </div>


    <div style="flex:1;">

        <label>
            {{ __('Ngày kết thúc') }}</label>

        <br>

        <input
            type="date"
            name="end_date"
            value="{{ old(
                'end_date',
                isset($promotion) && $promotion->end_date
                    ? $promotion->end_date->format('Y-m-d')
                    : ''
            ) }}"
            style="
                width:100%;
                padding:10px;
            "
        >

    </div>

</div>


<div style="margin-bottom:15px;">

    <label>

        <input
            type="checkbox"
            name="status"
            value="1"
            {{ old(
                'status',
                $promotion->status ?? true
            ) ? 'checked' : '' }}
        >

        {{ __('Hiển thị khuyến mãi') }}</label>

</div>
