<div style="margin-bottom:20px;">

    <label>
        {{ __('Tên Combo') }}</label>

    <br>

    <input
        type="text"
        name="name"
        value="{{ old('name', $combo->name ?? '') }}"
        required
        style="width:100%; padding:10px;"
    >

</div>

@include('admin.menu.partials.english-field', [
    'name' => 'name_en',
    'label' => __('Tên Combo'),
    'value' => $combo->name_en ?? '',
    'margin' => '20px',
])


<div style="margin-bottom:20px;">

    <label>
        Slug
    </label>

    <br>

    <input
        type="text"
        name="slug"
        value="{{ old('slug', $combo->slug ?? '') }}"
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:20px;">

    <label>
        {{ __('Mô tả') }}</label>

    <br>

    <textarea
        name="description"
        rows="5"
        style="width:100%; padding:10px;"
    >{{ old('description', $combo->description ?? '') }}</textarea>

</div>

@include('admin.menu.partials.english-field', [
    'name' => 'description_en',
    'label' => __('Mô tả'),
    'value' => $combo->description_en ?? '',
    'type' => 'textarea',
    'rows' => 5,
    'margin' => '20px',
])


<div data-image-field style="margin-bottom:20px;">

    <label>
        {{ __('Hình ảnh') }}</label>

    <br>

    <input
        type="file"
        name="image"
        accept=".jpg,.jpeg,.png,.webp"
        style="width:100%; padding:10px;"
    >

    @if(isset($combo) && $combo->image)
        <input type="hidden" name="remove_image" value="0" data-image-remove-value>
        <div data-current-image-preview>
            <p>{{ __('Ảnh hiện tại:') }}</p>
            <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->name }}" style="max-width:240px;">
            <p>{{ __('Để trống nếu muốn giữ ảnh hiện tại.') }}</p>
        </div>
        <button class="admin-image-remove-button" type="button" data-image-remove-toggle aria-pressed="false">{{ __('Xóa ảnh hiện tại') }}</button>
    @endif

</div>


<div style="margin-bottom:20px;">

    <label>
        {{ __('Giá bán') }}</label>

    <br>

    <input
        type="number"
        name="price"
        value="{{ old('price', $combo->price ?? '') }}"
        min="0"
        step="0.01"
        required
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:20px;">

    <label>
        {{ __('Giá gốc') }}</label>

    <br>

    <input
        type="number"
        name="original_price"
        value="{{ old('original_price', $combo->original_price ?? '') }}"
        min="0"
        step="0.01"
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:20px;">

    <label>
        {{ __('Ngày bắt đầu') }}</label>

    <br>

    <input
        type="date"
        name="start_date"
        value="{{ old('start_date', isset($combo) && $combo->start_date ? $combo->start_date->format('Y-m-d') : '') }}"
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:20px;">

    <label>
        {{ __('Ngày kết thúc') }}</label>

    <br>

    <input
        type="date"
        name="end_date"
        value="{{ old('end_date', isset($combo) && $combo->end_date ? $combo->end_date->format('Y-m-d') : '') }}"
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:20px;">

    <label>
        {{ __('Thứ tự') }}</label>

    <br>

    <input
        type="number"
        name="sort_order"
        value="{{ old('sort_order', $combo->sort_order ?? 0) }}"
        min="0"
        style="width:100%; padding:10px;"
    >

</div>


<h2>
    {{ __('Các món trong Combo') }}</h2>


<div id="combo-items">

    @php

        $selectedItems = old(
            'items',
            isset($combo)
                ? $combo->menuItems->map(function ($item) {
                    return [
                        'menu_item_id' => $item->id,
                        'quantity' => $item->pivot->quantity,
                    ];
                })->toArray()
                : [
                    [
                        'menu_item_id' => '',
                        'quantity' => 1,
                    ]
                ]
        );

    @endphp


    @foreach($selectedItems as $index => $selected)

        <div
            class="combo-item"
            style="
                display:flex;
                gap:10px;
                margin-bottom:10px;
            "
        >

            <select
                name="items[{{ $index }}][menu_item_id]"
                style="
                    flex:1;
                    padding:10px;
                "
            >

                <option value="">
                    {{ __('-- Chọn món --') }}</option>

                @foreach($menuItems as $menuItem)

                    <option
                        value="{{ $menuItem->id }}"
                        {{ $selected['menu_item_id'] == $menuItem->id ? 'selected' : '' }}
                    >
                        {{ $menuItem->name }}
                        -
                        {{ localized_price($menuItem->price) }}
                    </option>

                @endforeach

            </select>


            <input
                type="number"
                name="items[{{ $index }}][quantity]"
                value="{{ $selected['quantity'] }}"
                min="1"
                style="
                    width:100px;
                    padding:10px;
                "
            >


            <button
                type="button"
                onclick="removeComboItem(this)"
            >
                {{ __('Xóa') }}</button>

        </div>

    @endforeach

</div>


<button
    type="button"
    onclick="addComboItem()"
    style="
        margin-bottom:20px;
        padding:10px 15px;
    "
>
    {{ __('+ Thêm món') }}</button>


<div style="margin-bottom:20px;">

    @php
        $isActive = old('status', isset($combo) ? $combo->status : true);
    @endphp

    <label>

        <input
            type="checkbox"
            name="status"
            value="1"
            {{ $isActive ? 'checked' : '' }}
        >

        {{ __('Hiển thị') }}</label>

</div>


<script>

    let itemIndex =
        document.querySelectorAll('#combo-items .combo-item').length;


    function addComboItem()
    {
        const container =
            document.getElementById('combo-items');

        const div =
            document.createElement('div');

        div.className =
            'combo-item';

        div.style =
            'display:flex; gap:10px; margin-bottom:10px;';

        div.innerHTML = `

            <select
                name="items[${itemIndex}][menu_item_id]"
                style="flex:1; padding:10px;"
            >

                <option value="">
                    {{ __('-- Chọn món --') }}</option>

                @foreach($menuItems as $menuItem)

                    <option value="{{ $menuItem->id }}">
                        {{ $menuItem->name }}
                        -
                        {{ localized_price($menuItem->price) }}
                    </option>

                @endforeach

            </select>

            <input
                type="number"
                name="items[${itemIndex}][quantity]"
                value="1"
                min="1"
                style="width:100px; padding:10px;"
            >

            <button
                type="button"
                onclick="removeComboItem(this)"
            >
                {{ __('Xóa') }}</button>

        `;

        container.appendChild(div);

        itemIndex++;
    }


    function removeComboItem(button)
    {
        button
            .parentElement
            .remove();
    }

</script>