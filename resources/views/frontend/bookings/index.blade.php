@extends('frontend.layouts.app')

@section('title', __('Đặt bàn của tôi'))

@push('styles')
<style>
    .booking-management { max-width: 1100px; margin: 3rem auto; padding: 0 1rem; }
    .booking-management-card { background: #f6f4ee; border-top: 4px solid #a83d32; padding: clamp(1.25rem, 4vw, 2.25rem); }
    .booking-management-card h1 { font-family: Georgia, serif; }
    .booking-management-table { width: 100%; border-collapse: collapse; }
    .booking-management-table th, .booking-management-table td { padding: .8rem; border-bottom: 1px solid #ddd6ca; text-align: left; }
    @media(max-width: 700px) { .booking-management-table th:nth-child(2), .booking-management-table td:nth-child(2) { display:none; } }
</style>
@endpush

@section('content')
<section class="booking-management"><div class="booking-management-card">
    <h1>{{ __('Đặt bàn của tôi') }}</h1>
    @if (session('booking_cancelled')) <div class="alert alert-info" role="status">{{ __('Đặt bàn đã được hủy.') }}</div> @endif
    @if ($bookings->isEmpty())
        <p class="mt-3">{{ __('Bạn chưa có đặt bàn nào.') }}</p>
        <a class="btn btn-danger" href="{{ route('booking.create') }}">{{ __('Đặt bàn ngay') }}</a>
    @else
        <div class="table-responsive mt-3"><table class="booking-management-table"><thead><tr><th>{{ __('Mã đặt bàn') }}</th><th>{{ __('Nhà hàng') }}</th><th>{{ __('Ngày / giờ') }}</th><th>{{ __('Số người') }}</th><th>{{ __('Trạng thái') }}</th><th>{{ __('Thao tác') }}</th></tr></thead><tbody>
        @foreach ($bookings as $booking)
            <tr><td>{{ $booking->booking_code }}</td><td>{{ $booking->restaurant?->name ?? '-' }}</td><td>{{ $booking->booking_date->format('d/m/Y') }} · {{ substr($booking->booking_time, 0, 5) }}</td><td>{{ $booking->number_of_guests }}</td><td>{{ __(match ($booking->status) { 'pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy', 'completed' => 'Đã hoàn thành', default => $booking->status }) }}</td><td>@if (in_array($booking->status, ['pending', 'confirmed'], true))<form method="POST" action="{{ route('booking.mine.cancel', $booking) }}" onsubmit="return confirm(@json(__('Bạn chắc chắn muốn hủy đặt bàn này?'))) ">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Hủy') }}</button></form>@else — @endif</td></tr>
        @endforeach
        </tbody></table></div>
        {{ $bookings->links() }}
    @endif
</div></section>
@endsection
