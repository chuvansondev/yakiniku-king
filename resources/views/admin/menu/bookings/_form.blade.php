<div style="margin-bottom: 15px;">
    <label>Nhà hàng</label>

    <select
        name="restaurant_id"
        style="width: 100%; padding: 8px;"
        required
    >
        <option value="">-- Chọn nhà hàng --</option>

        @foreach($restaurants as $restaurant)

            <option
                value="{{ $restaurant->id }}"
                {{ old('restaurant_id', $booking->restaurant_id ?? '') == $restaurant->id ? 'selected' : '' }}
            >
                {{ $restaurant->name }}
            </option>

        @endforeach

    </select>
</div>


<div style="margin-bottom: 15px;">
    <label>Tên khách hàng</label>

    <input
        type="text"
        name="customer_name"
        value="{{ old('customer_name', $booking->customer_name ?? '') }}"
        style="width: 100%; padding: 8px;"
        required
    >
</div>


<div style="margin-bottom: 15px;">
    <label>Số điện thoại</label>

    <input
        type="text"
        name="phone"
        value="{{ old('phone', $booking->phone ?? '') }}"
        style="width: 100%; padding: 8px;"
        required
    >
</div>


<div style="margin-bottom: 15px;">
    <label>Email</label>

    <input
        type="email"
        name="email"
        value="{{ old('email', $booking->email ?? '') }}"
        style="width: 100%; padding: 8px;"
    >
</div>


<div style="display: flex; gap: 15px; margin-bottom: 15px;">

    <div style="flex: 1;">

        <label>Ngày đặt</label>

        <input
            type="date"
            name="booking_date"
            value="{{ old(
                'booking_date',
                isset($booking) && $booking->booking_date
                    ? $booking->booking_date->format('Y-m-d')
                    : ''
            ) }}"
            style="width: 100%; padding: 8px;"
            required
        >

    </div>


    <div style="flex: 1;">

        <label>Giờ đặt</label>

        <input
            type="time"
            name="booking_time"
            value="{{ old(
                'booking_time',
                isset($booking) && $booking->booking_time
                    ? substr($booking->booking_time, 0, 5)
                    : ''
            ) }}"
            style="width: 100%; padding: 8px;"
            required
        >

    </div>

</div>


<div style="margin-bottom: 15px;">

    <label>Số người</label>

    <input
        type="number"
        name="number_of_guests"
        min="1"
        max="100"
        value="{{ old('number_of_guests', $booking->number_of_guests ?? 1) }}"
        style="width: 100%; padding: 8px;"
        required
    >

</div>


<div style="margin-bottom: 15px;">

    <label>Ghi chú</label>

    <textarea
        name="note"
        rows="4"
        style="width: 100%; padding: 8px;"
    >{{ old('note', $booking->note ?? '') }}</textarea>

</div>


<div style="margin-bottom: 15px;">

    <label>Trạng thái</label>

    <select
        name="status"
        style="width: 100%; padding: 8px;"
        required
    >

        @php
            $currentStatus = old(
                'status',
                $booking->status ?? \App\Enums\BookingStatus::Pending->value
            );
        @endphp

        <option
            value="pending"
            {{ $currentStatus === \App\Enums\BookingStatus::Pending->value ? 'selected' : '' }}
        >
            Chờ xác nhận
        </option>

        <option
            value="confirmed"
            {{ $currentStatus === \App\Enums\BookingStatus::Confirmed->value ? 'selected' : '' }}
        >
            Đã xác nhận
        </option>

        <option
            value="cancelled"
            {{ $currentStatus === \App\Enums\BookingStatus::Cancelled->value ? 'selected' : '' }}
        >
            Đã hủy
        </option>

        <option
            value="completed"
            {{ $currentStatus === \App\Enums\BookingStatus::Completed->value ? 'selected' : '' }}
        >
            Đã hoàn thành
        </option>

    </select>

</div>


<button type="submit">
    {{ $buttonText ?? 'Lưu' }}
</button>
