<div class="admin-booking-field admin-booking-field-wide">
    <label for="booking-restaurant">{{ __('Nhà hàng') }}</label>
    <select id="booking-restaurant" name="restaurant_id" required>
        <option value="">-- {{ __('Chọn nhà hàng') }} --</option>
        @foreach ($restaurants as $restaurant)
            <option value="{{ $restaurant->id }}" @selected(old('restaurant_id', $booking->restaurant_id ?? '') == $restaurant->id)>{{ $restaurant->name }}</option>
        @endforeach
    </select>
    @error('restaurant_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-field">
    <label for="booking-customer-name">{{ __('Tên khách hàng') }}</label>
    <input id="booking-customer-name" type="text" name="customer_name" value="{{ old('customer_name', $booking->customer_name ?? '') }}" required>
    @error('customer_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-field">
    <label for="booking-phone">{{ __('Số điện thoại') }}</label>
    <input id="booking-phone" type="text" name="phone" value="{{ old('phone', $booking->phone ?? '') }}" required>
    @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-field">
    <label for="booking-email">{{ __('Email') }}</label>
    <input id="booking-email" type="email" name="email" value="{{ old('email', $booking->email ?? '') }}">
    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-field">
    <label for="booking-date">{{ __('Ngày đặt') }}</label>
    <input id="booking-date" type="date" name="booking_date" value="{{ old('booking_date', isset($booking) && $booking->booking_date ? $booking->booking_date->format('Y-m-d') : '') }}" required>
    @error('booking_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-field">
    <label for="booking-time">{{ __('Giờ đặt') }}</label>
    <input id="booking-time" type="time" name="booking_time" value="{{ old('booking_time', isset($booking) && $booking->booking_time ? substr($booking->booking_time, 0, 5) : '') }}" required>
    @error('booking_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-field">
    <label for="booking-guests">{{ __('Số người') }}</label>
    <input id="booking-guests" type="number" name="number_of_guests" min="1" max="100" value="{{ old('number_of_guests', $booking->number_of_guests ?? 1) }}" required>
    @error('number_of_guests')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-table-fields" data-booking-table-fields>
    <div class="admin-booking-field admin-booking-floor-field">
        <label for="booking-floor">{{ __('Tầng') }}</label>
        <select id="booking-floor" name="floor" required>
            @php($selectedFloor = old('floor', $booking->floor ?? ($tables->first()?->floor ?? 1)))
            @forelse ($tables->pluck('floor')->unique()->sort() as $floorOption)
                <option value="{{ $floorOption }}" @selected((int) $selectedFloor === (int) $floorOption)>{{ __('Tầng') }} {{ $floorOption }}</option>
            @empty
                <option value="1" @selected((int) $selectedFloor === 1)>{{ __('Tầng 1') }}</option>
            @endforelse
        </select>
    </div>
    <fieldset class="admin-booking-table-picker">
        <legend>{{ __('Chọn bàn') }}</legend>
        <div class="admin-booking-table-list" data-booking-tables>
            @php($selectedTables = old('table_codes', $booking->table_codes ?? []))
            @foreach ($tables as $table)
                <label data-admin-table-option data-floor="{{ $table->floor }}" class="admin-booking-table-option">
                    <input type="checkbox" name="table_codes[]" value="{{ $table->code }}" data-capacity="{{ $table->capacity }}" @checked(in_array($table->code, $selectedTables, true))>
                    <span>{{ $table->code }}</span><small>{{ $table->capacity }} {{ __('người') }}</small>
                </label>
            @endforeach
        </div>
        <small class="admin-booking-availability" data-booking-availability-message>{{ __('Chọn ngày và giờ để kiểm tra bàn trống.') }}</small>
        @error('floor')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('table_codes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('table_codes.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </fieldset>
</div>

<div class="admin-booking-field admin-booking-field-wide">
    <label for="booking-note">{{ __('Ghi chú') }}</label>
    <textarea id="booking-note" name="note" rows="3">{{ old('note', $booking->note ?? '') }}</textarea>
    @error('note')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-booking-field">
    <label for="booking-status">{{ __('Trạng thái') }}</label>
    <select id="booking-status" name="status" required>
        <option value="pending" @selected(old('status', $booking->status ?? \App\Enums\BookingStatus::Pending->value) === \App\Enums\BookingStatus::Pending->value)>{{ __('Chờ xác nhận') }}</option>
        <option value="confirmed" @selected(old('status', $booking->status ?? \App\Enums\BookingStatus::Pending->value) === \App\Enums\BookingStatus::Confirmed->value)>{{ __('Đã xác nhận') }}</option>
        <option value="cancelled" @selected(old('status', $booking->status ?? \App\Enums\BookingStatus::Pending->value) === \App\Enums\BookingStatus::Cancelled->value)>{{ __('Đã hủy') }}</option>
        <option value="completed" @selected(old('status', $booking->status ?? \App\Enums\BookingStatus::Pending->value) === \App\Enums\BookingStatus::Completed->value)>{{ __('Đã hoàn thành') }}</option>
    </select>
    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="admin-form-actions">
    <button type="submit">{{ $buttonText }}</button>
    <a href="{{ route('admin.menu.bookings.index') }}">{{ __('Quay lại danh sách') }}</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const fields = document.querySelector('[data-booking-table-fields]');
    const form = fields?.closest('form');
    if (!form) return;
    const restaurant = form.querySelector('[name="restaurant_id"]');
    const date = form.querySelector('[name="booking_date"]');
    const time = form.querySelector('[name="booking_time"]');
    const floor = form.querySelector('[name="floor"]');
    const list = form.querySelector('[data-booking-tables]');
    const message = form.querySelector('[data-booking-availability-message]');
    const excludedBooking = @json(isset($booking) ? $booking->id : null);
    const labels = {
        floor: @json(__('Tầng')),
        available: @json(__('Tình trạng bàn đã được cập nhật.')),
        chooseTime: @json(__('Chọn ngày và giờ để kiểm tra bàn trống.')),
        loadError: @json(__('Không thể tải sơ đồ bàn. Vui lòng thử lại.')),
        people: @json(__('người')),
    };

    const filterFloor = () => list.querySelectorAll('[data-admin-table-option]').forEach(label => {
        const matches = Number(label.dataset.floor) === Number(floor.value);
        label.hidden = !matches;
        if (!matches) label.querySelector('input').checked = false;
    });

    const renderTables = (tables, unavailable = [], previousFloor = floor.value) => {
        const floors = [...new Set(tables.map(table => Number(table.floor)))].sort((a, b) => a - b);
        floor.replaceChildren(...(floors.length ? floors : [1]).map(value => new Option(`${labels.floor} ${value}`, value)));
        if (floors.includes(Number(previousFloor))) floor.value = previousFloor;
        list.replaceChildren();
        tables.forEach(table => {
            const label = document.createElement('label');
            label.dataset.adminTableOption = '';
            label.dataset.floor = table.floor;
            label.className = 'admin-booking-table-option';
            const input = document.createElement('input');
            input.type = 'checkbox'; input.name = 'table_codes[]'; input.value = table.code;
            input.dataset.capacity = table.capacity; input.disabled = unavailable.includes(table.code);
            const code = document.createElement('span'); code.textContent = table.code;
            const capacity = document.createElement('small'); capacity.textContent = `${table.capacity} ${labels.people}`;
            label.append(input, code, capacity); list.append(label);
        });
        filterFloor();
        message.textContent = date.value && time.value ? labels.available : labels.chooseTime;
    };

    const refresh = async (reloadTables = false) => {
        if (!restaurant.value) return;
        const params = new URLSearchParams({ restaurant_id: restaurant.value });
        if (date.value) params.set('booking_date', date.value);
        if (time.value) params.set('booking_time', time.value);
        if (excludedBooking) params.set('exclude_booking_id', excludedBooking);
        try {
            const response = await fetch(`{{ route('booking.availability') }}?${params}`, {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) throw new Error('availability');
            const data = await response.json();
            if (reloadTables) {
                renderTables(data.tables, data.unavailable);
                return;
            }
            list.querySelectorAll('[data-admin-table-option]').forEach(label => {
                const input = label.querySelector('input');
                input.disabled = data.unavailable.includes(input.value);
                if (input.disabled) input.checked = false;
            });
            message.textContent = date.value && time.value ? labels.available : labels.chooseTime;
        } catch {
            message.textContent = labels.loadError;
        }
    };

    restaurant.addEventListener('change', () => refresh(true));
    date.addEventListener('change', () => refresh());
    time.addEventListener('change', () => refresh());
    floor.addEventListener('change', filterFloor);
    filterFloor();
    if (date.value && time.value) refresh();
});
</script>
