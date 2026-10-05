<div style="margin-bottom: 15px;">
    <label>Họ tên</label>

    <input
        type="text"
        name="name"
        value="{{ old('name', $lead->name ?? '') }}"
        style="width: 100%; padding: 8px;"
        required
    >
</div>


<div style="margin-bottom: 15px;">
    <label>Số điện thoại</label>

    <input
        type="text"
        name="phone"
        value="{{ old('phone', $lead->phone ?? '') }}"
        style="width: 100%; padding: 8px;"
    >
</div>


<div style="margin-bottom: 15px;">
    <label>Email</label>

    <input
        type="email"
        name="email"
        value="{{ old('email', $lead->email ?? '') }}"
        style="width: 100%; padding: 8px;"
    >
</div>


<div style="margin-bottom: 15px;">
    <label>Nội dung</label>

    <textarea
        name="message"
        rows="5"
        style="width: 100%; padding: 8px;"
    >{{ old('message', $lead->message ?? '') }}</textarea>
</div>


<div style="margin-bottom: 15px;">
    <label>Trạng thái</label>

    @php
        $currentStatus = old(
            'status',
            $lead->status ?? \App\Enums\LeadStatus::New->value
        );
    @endphp

    <select
        name="status"
        style="width: 100%; padding: 8px;"
        required
    >

        <option
            value="new"
            {{ $currentStatus === \App\Enums\LeadStatus::New->value ? 'selected' : '' }}
        >
            Mới
        </option>

        <option
            value="read"
            {{ $currentStatus === \App\Enums\LeadStatus::Read->value ? 'selected' : '' }}
        >
            Đã xem
        </option>

        <option
            value="contacted"
            {{ $currentStatus === \App\Enums\LeadStatus::Contacted->value ? 'selected' : '' }}
        >
            Đã liên hệ
        </option>

    </select>
</div>


<button type="submit">
    {{ $buttonText ?? 'Lưu' }}
</button>
