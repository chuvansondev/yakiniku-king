@extends('admin.layouts.app')

@section('title', __('Báo cáo vận hành'))
@section('page-title', __('Báo cáo vận hành'))

@section('content')
<div class="ops-report">
    <header class="ops-header">
        <div><p class="ops-kicker">{{ __('ĐẶT BÀN · HIỆU SUẤT CHI NHÁNH · NHU CẦU') }}</p><h1>{{ __('Báo cáo vận hành') }}</h1><p>{{ __('Theo dõi nhu cầu khách và hiệu quả tiếp nhận đặt bàn theo thời gian.') }}</p></div>
        <a class="ops-export" href="{{ route('admin.reports.operations.export', request()->query()) }}">{{ __('Tải CSV') }} <span aria-hidden="true">↓</span></a>
    </header>

    <form class="ops-filters" method="GET" action="{{ route('admin.reports.operations') }}">
        <label>{{ __('Từ ngày') }}<input type="date" name="from" value="{{ $from->toDateString() }}"></label>
        <label>{{ __('Đến ngày') }}<input type="date" name="to" value="{{ $to->toDateString() }}"></label>
        <label>{{ __('Nhà hàng') }}<select name="restaurant_id"><option value="">{{ __('Tất cả chi nhánh') }}</option>@foreach($restaurants as $restaurant)<option value="{{ $restaurant->id }}" @selected((string)$restaurantId === (string)$restaurant->id)>{{ $restaurant->name }}</option>@endforeach</select></label>
        <button type="submit">{{ __('Xem báo cáo') }}</button>
    </form>

    <section class="ops-kpis" aria-label="{{ __('Chỉ số vận hành') }}">
        <article><span>{{ __('Tổng lượt đặt') }}</span><strong>{{ number_format($summary->total ?? 0) }}</strong><small>{{ __('theo ngày đặt') }}</small></article>
        <article><span>{{ __('Đã tiếp nhận') }}</span><strong>{{ number_format(($summary->confirmed ?? 0) + ($summary->completed ?? 0)) }}</strong><small>{{ __('xác nhận hoặc hoàn thành') }}</small></article>
        <article><span>{{ __('Tỷ lệ hủy') }}</span><strong>{{ ($summary->total ?? 0) ? number_format(($summary->cancelled / $summary->total) * 100, 1) : '0.0' }}%</strong><small>{{ number_format($summary->cancelled ?? 0) }} {{ __('lượt đã hủy') }}</small></article>
        <article><span>{{ __('Tổng khách dự kiến') }}</span><strong>{{ number_format($summary->guests ?? 0) }}</strong><small>{{ __('khách trong các lượt đặt') }}</small></article>
        <article><span>{{ __('Chờ xử lý') }}</span><strong>{{ number_format($summary->pending ?? 0) }}</strong><small>{{ __('cần xác nhận') }}</small></article>
    </section>

    <div class="ops-grid">
        <section class="ops-panel ops-span-2"><header><div><span>{{ __('NHỊP ĐỘ') }}</span><h2>{{ __('Lượt đặt và khách theo ngày') }}</h2></div></header>
            @php($maxDaily = max(1, (int)$daily->max('bookings')))
            <div class="ops-daily">@forelse($daily as $day)<div class="ops-day"><div class="ops-day-value">{{ $day->bookings }} {{ __('lượt') }}<small>{{ $day->guests }} {{ __('khách') }}</small></div><div class="ops-bar-track"><i style="height: {{ max(5, round($day->bookings / $maxDaily * 100)) }}%"></i></div><time>{{ \Illuminate\Support\Carbon::parse($day->booking_date)->format('d/m') }}</time></div>@empty<p class="ops-empty">{{ __('Không có lượt đặt trong khoảng thời gian này.') }}</p>@endforelse</div>
        </section>

        <section class="ops-panel"><header><div><span>{{ __('SO SÁNH CHI NHÁNH') }}</span><h2>{{ __('Hiệu quả tiếp nhận') }}</h2></div></header>
            <div class="ops-table-wrap"><table><thead><tr><th>{{ __('Nhà hàng') }}</th><th>{{ __('Lượt đặt') }}</th><th>{{ __('Đã nhận') }}</th><th>{{ __('Hủy') }}</th></tr></thead><tbody>@forelse($byRestaurant as $branch)<tr><td>{{ $branch->name }}</td><td>{{ number_format($branch->bookings) }}</td><td>{{ number_format($branch->served) }}</td><td>{{ number_format($branch->cancelled) }}</td></tr>@empty<tr><td colspan="4" class="ops-empty">{{ __('Chưa có dữ liệu.') }}</td></tr>@endforelse</tbody></table></div>
        </section>

        <section class="ops-panel"><header><div><span>{{ __('PHÂN BỐ NHU CẦU') }}</span><h2>{{ __('Khung giờ cao điểm') }}</h2></div></header>
            @php($maxHour = max(1, (int)$byHour->max('bookings')))
            <div class="ops-ranked">@forelse($byHour as $slot)<div><span>{{ sprintf('%02d:00', $slot->hour) }}–{{ sprintf('%02d:00', ($slot->hour + 1) % 24) }}</span><i><b style="width: {{ round($slot->bookings / $maxHour * 100) }}%"></b></i><strong>{{ $slot->bookings }}</strong></div>@empty<p class="ops-empty">{{ __('Chưa có dữ liệu.') }}</p>@endforelse</div>
        </section>

        <section class="ops-panel"><header><div><span>{{ __('LỊCH HOẠT ĐỘNG') }}</span><h2>{{ __('Lượt đặt theo thứ') }}</h2></div></header>
            @php($weekdayNames = [1 => __('Chủ nhật'), 2 => __('Thứ hai'), 3 => __('Thứ ba'), 4 => __('Thứ tư'), 5 => __('Thứ năm'), 6 => __('Thứ sáu'), 7 => __('Thứ bảy')])
            @php($maxWeekday = max(1, (int)$byWeekday->max('bookings')))
            <div class="ops-ranked">@foreach($weekdayNames as $number => $name)@php($count = (int)($byWeekday->get($number)->bookings ?? 0))<div><span>{{ $name }}</span><i><b style="width: {{ round($count / $maxWeekday * 100) }}%"></b></i><strong>{{ $count }}</strong></div>@endforeach</div>
        </section>

        <section class="ops-panel"><header><div><span>{{ __('PHÂN BỔ BÀN') }}</span><h2>{{ __('Bàn được chọn nhiều') }}</h2></div></header>
            <div class="ops-ranked">@forelse($tableDemand as $table)<div><span>{{ __('Bàn :code', ['code' => $table['table']]) }}<small>{{ $table['guests'] }} {{ __('khách') }}</small></span><i><b style="width: {{ round($table['bookings'] / max(1, (int)$tableDemand->max('bookings')) * 100) }}%"></b></i><strong>{{ $table['bookings'] }}</strong></div>@empty<p class="ops-empty">{{ __('Chưa có dữ liệu bàn.') }}</p>@endforelse</div>
        </section>
    </div>
    <p class="ops-footnote">{{ __('Chỉ số được tính theo ngày đặt và trạng thái booking hiện tại. Hệ thống chưa ghi nhận hóa đơn/doanh thu nên báo cáo không ước tính doanh thu.') }}</p>
</div>

<style>
.ops-report{max-width:1500px;margin:0 auto;color:#202923}.ops-header{display:flex;justify-content:space-between;align-items:end;gap:20px;margin:4px 0 24px}.ops-header h1{margin:5px 0;font-size:30px}.ops-header p{margin:0;color:#737d76}.ops-kicker,.ops-panel header span{font-size:10px;letter-spacing:.13em;font-weight:700;color:#8a6a4d}.ops-export,.ops-filters button{border:0;border-radius:7px;background:#c84b3b;color:white;padding:11px 15px;font-weight:700;text-decoration:none}.ops-filters{display:grid;grid-template-columns:1fr 1fr 1.4fr auto;gap:12px;align-items:end;padding:16px;background:#fff;border:1px solid #e3e8e3;border-radius:12px;margin-bottom:16px}.ops-filters label{display:grid;gap:6px;font-size:11px;font-weight:700}.ops-filters input,.ops-filters select{height:40px;border:1px solid #d6ded7;border-radius:6px;background:white;padding:0 10px;color:#26332b}.ops-kpis{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px;margin-bottom:16px}.ops-kpis article,.ops-panel{background:white;border:1px solid #e3e8e3;border-radius:11px}.ops-kpis article{padding:16px;display:grid;gap:7px}.ops-kpis span,.ops-kpis small{color:#737d76;font-size:11px}.ops-kpis strong{font-size:28px}.ops-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.ops-panel{padding:18px;min-width:0}.ops-span-2{grid-column:span 2}.ops-panel header{margin-bottom:16px}.ops-panel h2{font-size:17px;margin:5px 0 0}.ops-daily{min-height:230px;display:flex;align-items:stretch;gap:8px;overflow-x:auto;padding:8px 0}.ops-day{min-width:42px;flex:1;display:flex;flex-direction:column;align-items:center;justify-content:end;gap:8px}.ops-day-value{font-size:10px;text-align:center;white-space:nowrap}.ops-day-value small{display:block;color:#8b958d}.ops-bar-track{height:130px;width:100%;display:flex;align-items:end;justify-content:center;border-bottom:1px solid #e3e8e3}.ops-bar-track i{width:min(28px,70%);min-height:4px;border-radius:4px 4px 0 0;background:#537963}.ops-day:last-child .ops-bar-track i{background:#c84b3b}.ops-day time{font-size:10px;color:#737d76}.ops-table-wrap{overflow-x:auto}.ops-panel table{width:100%;border-collapse:collapse;font-size:12px}.ops-panel th,.ops-panel td{text-align:left;padding:10px 8px;border-bottom:1px solid #edf0ed}.ops-panel th{color:#737d76;font-size:10px}.ops-ranked{display:grid;gap:15px}.ops-ranked>div{display:grid;grid-template-columns:minmax(90px,1fr) 2fr 30px;gap:10px;align-items:center;font-size:12px}.ops-ranked>div>span small{display:block;font-size:10px;color:#8b958d}.ops-ranked i{height:8px;border-radius:9px;background:#edf0ed;overflow:hidden}.ops-ranked i b{display:block;height:100%;border-radius:9px;background:#537963}.ops-ranked strong{text-align:right}.ops-empty{color:#737d76;font-size:12px}.ops-footnote{margin:14px 0;color:#737d76;font-size:11px}@media(max-width:1000px){.ops-kpis{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:680px){.ops-header{align-items:start;flex-direction:column}.ops-filters{grid-template-columns:1fr 1fr}.ops-filters label:nth-child(3){grid-column:span 2}.ops-filters button{grid-column:span 2}.ops-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}.ops-kpis article:last-child{grid-column:span 2}.ops-grid{grid-template-columns:1fr}.ops-span-2{grid-column:auto}}
</style>
@endsection
