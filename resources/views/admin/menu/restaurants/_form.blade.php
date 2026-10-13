<div class="restaurant-field">
    <label for="restaurant-name">{{ __('Tên nhà hàng') }}</label>
    <input id="restaurant-name" type="text" name="name" value="{{ old('name', $restaurant->name ?? '') }}" required>
</div>

<div class="restaurant-field">
    <label for="restaurant-name-en">{{ __('Tên nhà hàng') }} (English)</label>
    <input id="restaurant-name-en" type="text" name="name_en" value="{{ old('name_en', $restaurant->name_en ?? '') }}">
</div>

<fieldset class="restaurant-tables-panel">
    <legend>{{ __('Sơ đồ bàn và sức chứa') }}</legend>
    <p class="restaurant-tables-help">{{ __('Khai báo mã bàn, tầng và sức chứa cho nhà hàng. Bàn bỏ chọn sẽ được ngừng hoạt động; dữ liệu đặt bàn cũ vẫn được giữ.') }}</p>
    <div class="restaurant-table-columns" aria-hidden="true">
        <span>{{ __('Mã bàn') }}</span><span>{{ __('Tầng') }}</span><span>{{ __('Sức chứa') }}</span><span>{{ __('Thứ tự') }}</span><span>{{ __('Trạng thái') }}</span><span></span>
    </div>
    <div data-restaurant-tables>
        @php($configuredTables = old('tables', isset($restaurant) ? $restaurant->tables->map(fn ($table) => ['code' => $table->code, 'floor' => $table->floor, 'capacity' => $table->capacity, 'position' => $table->position, 'status' => $table->status])->all() : []))
        @foreach ($configuredTables as $index => $table)
            <div data-table-row class="restaurant-table-row">
                <label class="visually-hidden">{{ __('Mã bàn') }}<input name="tables[{{ $index }}][code]" value="{{ $table['code'] }}" required maxlength="32"></label>
                <label class="visually-hidden">{{ __('Tầng') }}<input name="tables[{{ $index }}][floor]" type="number" min="1" max="20" value="{{ $table['floor'] }}" required></label>
                <label class="visually-hidden">{{ __('Sức chứa') }}<input name="tables[{{ $index }}][capacity]" type="number" min="1" max="100" value="{{ $table['capacity'] }}" required></label>
                <label class="visually-hidden">{{ __('Thứ tự') }}<input name="tables[{{ $index }}][position]" type="number" min="0" max="1000" value="{{ $table['position'] ?? $index }}"></label>
                <label class="restaurant-table-status"><input name="tables[{{ $index }}][status]" type="checkbox" value="1" @checked($table['status'] ?? true)> {{ __('Đang hoạt động') }}</label>
                <button class="restaurant-table-remove" type="button" data-remove-table>{{ __('Bỏ bàn') }}</button>
            </div>
        @endforeach
    </div>
    <button class="restaurant-table-add" type="button" data-add-table>＋ {{ __('Thêm bàn') }}</button>
    @error('tables')<div class="restaurant-form-error">{{ $message }}</div>@enderror
    @error('tables.*.code')<div class="restaurant-form-error">{{ $message }}</div>@enderror
</fieldset>

<div class="restaurant-field">
    <label for="restaurant-address">{{ __('Địa chỉ') }}</label>
    <textarea id="restaurant-address" name="address" rows="3" required>{{ old('address', $restaurant->address ?? '') }}</textarea>
</div>

<div class="restaurant-field">
    <label for="restaurant-address-en">{{ __('Địa chỉ') }} (English)</label>
    <textarea id="restaurant-address-en" name="address_en" rows="3">{{ old('address_en', $restaurant->address_en ?? '') }}</textarea>
</div>

<div class="restaurant-field">
    <label for="restaurant-phone">{{ __('Số điện thoại') }}</label>
    <input id="restaurant-phone" type="text" name="phone" value="{{ old('phone', $restaurant->phone ?? '') }}">
</div>

<div class="restaurant-field-row">
    <div class="restaurant-field">
        <label for="restaurant-latitude">Latitude</label>
        <input id="restaurant-latitude" type="number" step="any" name="latitude" value="{{ old('latitude', $restaurant->latitude ?? '') }}">
    </div>
    <div class="restaurant-field">
        <label for="restaurant-longitude">Longitude</label>
        <input id="restaurant-longitude" type="number" step="any" name="longitude" value="{{ old('longitude', $restaurant->longitude ?? '') }}">
    </div>
</div>

<div class="restaurant-field restaurant-field-wide">
    <label for="restaurant-map-url">Google Maps URL</label>
    <input id="restaurant-map-url" type="url" name="google_map_url" value="{{ old('google_map_url', $restaurant->google_map_url ?? '') }}" placeholder="{{ __('admin_lists.map_url_placeholder') }}">
</div>

<div class="restaurant-field-row">
    <div class="restaurant-field">
        <label for="restaurant-opening-time">{{ __('Giờ mở cửa') }}</label>
        <input id="restaurant-opening-time" type="time" name="opening_time" value="{{ old('opening_time', isset($restaurant) && $restaurant->opening_time ? substr($restaurant->opening_time, 0, 5) : '') }}">
    </div>
    <div class="restaurant-field">
        <label for="restaurant-closing-time">{{ __('Giờ đóng cửa') }}</label>
        <input id="restaurant-closing-time" type="time" name="closing_time" value="{{ old('closing_time', isset($restaurant) && $restaurant->closing_time ? substr($restaurant->closing_time, 0, 5) : '') }}">
    </div>
</div>

<div data-image-field class="restaurant-field restaurant-field-wide">
    <label for="restaurant-image">{{ __('Ảnh nhà hàng') }}</label>
    <input id="restaurant-image" type="file" name="image" accept="image/*">
    @if(isset($restaurant) && $restaurant->image)
        <input type="hidden" name="remove_image" value="0" data-image-remove-value>
        <div data-current-image-preview class="restaurant-image-preview">
            <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}" width="150">
        </div>
        <button class="admin-image-remove-button" type="button" data-image-remove-toggle aria-pressed="false">{{ __('Xóa ảnh hiện tại') }}</button>
    @endif
</div>

<div class="restaurant-field restaurant-status-field">
    <label><input type="checkbox" name="status" value="1" {{ old('status', $restaurant->status ?? true) ? 'checked' : '' }}> {{ __('Đang hoạt động') }}</label>
</div>

<div class="admin-form-actions">
    <button type="submit">{{ $buttonText }}</button>
    <a href="{{ route('admin.menu.restaurants.index') }}">{{ __('Quay lại danh sách') }}</a>
</div>

<template data-table-template>
    <div data-table-row class="restaurant-table-row">
        <label class="visually-hidden">{{ __('Mã bàn') }}<input data-field="code" required maxlength="32"></label>
        <label class="visually-hidden">{{ __('Tầng') }}<input data-field="floor" type="number" min="1" max="20" value="1" required></label>
        <label class="visually-hidden">{{ __('Sức chứa') }}<input data-field="capacity" type="number" min="1" max="100" value="4" required></label>
        <label class="visually-hidden">{{ __('Thứ tự') }}<input data-field="position" type="number" min="0" max="1000" value="0"></label>
        <label class="restaurant-table-status"><input data-field="status" type="checkbox" value="1" checked> {{ __('Đang hoạt động') }}</label>
        <button class="restaurant-table-remove" type="button" data-remove-table>{{ __('Bỏ bàn') }}</button>
    </div>
</template>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.querySelector('[data-restaurant-tables]');
    const template = document.querySelector('[data-table-template]');
    if (!list || !template) return;
    let nextIndex = Math.max(-1, ...[...list.querySelectorAll('[name^="tables["]')].map(input => Number(input.name.match(/^tables\[(\d+)\]/)?.[1] ?? -1))) + 1;
    document.querySelector('[data-add-table]')?.addEventListener('click', () => {
        const row = template.content.firstElementChild.cloneNode(true);
        row.querySelectorAll('[data-field]').forEach(input => {
            input.name = `tables[${nextIndex}][${input.dataset.field}]`;
            if (input.dataset.field === 'position') input.value = String(nextIndex);
        });
        nextIndex++;
        list.append(row);
    });
    list.addEventListener('click', event => {
        if (event.target.closest('[data-remove-table]')) event.target.closest('[data-table-row]').remove();
    });
});
</script>
