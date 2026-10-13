<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Restaurant;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class OperationsReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'restaurant_id' => ['nullable', 'integer', 'exists:restaurants,id'],
        ]);
        $from = CarbonImmutable::parse($filters['from'] ?? today()->subDays(29)->toDateString())->startOfDay();
        $to = CarbonImmutable::parse($filters['to'] ?? today()->toDateString())->endOfDay();
        abort_if($from->diffInDays($to) > 366, 422, 'Date range must not exceed 367 days.');
        $restaurantId = $filters['restaurant_id'] ?? null;
        $restaurants = Restaurant::query()->orderBy('name')->get(['id', 'name']);
        $base = Booking::query()->whereBetween('booking_date', [$from->toDateString(), $to->toDateString()])
            ->when($restaurantId, fn ($query) => $query->where('restaurant_id', $restaurantId));

        $summary = (clone $base)->selectRaw('COUNT(*) as total, SUM(status = ?) as confirmed, SUM(status = ?) as completed, SUM(status = ?) as cancelled, SUM(status = ?) as pending, SUM(number_of_guests) as guests', [
            BookingStatus::Confirmed->value, BookingStatus::Completed->value, BookingStatus::Cancelled->value, BookingStatus::Pending->value,
        ])->first();
        $daily = (clone $base)->selectRaw('booking_date, COUNT(*) as bookings, SUM(status IN (?, ?)) as served, SUM(status = ?) as cancelled, SUM(number_of_guests) as guests', [
            BookingStatus::Confirmed->value, BookingStatus::Completed->value, BookingStatus::Cancelled->value,
        ])->groupBy('booking_date')->orderBy('booking_date')->get();
        $byRestaurant = (clone $base)->join('restaurants', 'restaurants.id', '=', 'bookings.restaurant_id')
            ->selectRaw('restaurants.id, restaurants.name, COUNT(*) as bookings, SUM(bookings.status IN (?, ?)) as served, SUM(bookings.status = ?) as cancelled, SUM(bookings.number_of_guests) as guests', [
                BookingStatus::Confirmed->value, BookingStatus::Completed->value, BookingStatus::Cancelled->value,
            ])->groupBy('restaurants.id', 'restaurants.name')->orderByDesc('bookings')->get();
        $byHour = (clone $base)->get(['booking_time', 'number_of_guests'])->groupBy(fn (Booking $booking) => (int) substr((string) $booking->booking_time, 0, 2))
            ->map(fn ($rows, $hour) => (object) ['hour' => $hour, 'bookings' => $rows->count(), 'guests' => $rows->sum('number_of_guests')])
            ->sortByDesc('bookings')->take(8)->values();
        $byWeekday = (clone $base)->get(['booking_date'])->groupBy(fn (Booking $booking) => CarbonImmutable::parse($booking->booking_date)->dayOfWeekIso % 7 + 1)
            ->map(fn ($rows, $weekday) => (object) ['weekday' => $weekday, 'bookings' => $rows->count()]);
        $tableDemand = (clone $base)->whereNotNull('table_codes')->get(['table_codes', 'number_of_guests'])
            ->flatMap(fn (Booking $booking) => collect($booking->table_codes ?? [])->map(fn ($table) => [
                'table' => (string) $table,
                'guests' => (int) $booking->number_of_guests,
            ]))->groupBy('table')->map(fn ($rows, $table) => [
                'table' => $table, 'bookings' => $rows->count(), 'guests' => $rows->sum('guests'),
            ])->sortByDesc('bookings')->take(10)->values();

        return view('admin.reports.operations', compact('from', 'to', 'restaurantId', 'restaurants', 'summary', 'daily', 'byRestaurant', 'byHour', 'byWeekday', 'tableDemand'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'restaurant_id' => ['nullable', 'integer', 'exists:restaurants,id'],
        ]);
        $from = $filters['from'] ?? today()->subDays(29)->toDateString();
        $to = $filters['to'] ?? today()->toDateString();
        abort_if(CarbonImmutable::parse($from)->diffInDays(CarbonImmutable::parse($to)) > 366, 422);
        $rows = Booking::query()->with('restaurant')->whereBetween('booking_date', [$from, $to])
            ->when($filters['restaurant_id'] ?? null, fn ($query, $id) => $query->where('restaurant_id', $id))
            ->orderBy('booking_date')->orderBy('booking_time')->get();

        return response()->streamDownload(function () use ($rows): void {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['Mã đặt bàn', 'Ngày', 'Giờ', 'Nhà hàng', 'Khách', 'Số người', 'Bàn', 'Trạng thái', 'Ghi chú']);
            foreach ($rows as $booking) {
                fputcsv($stream, [$booking->booking_code, $booking->booking_date?->format('Y-m-d'), $booking->booking_time, $booking->restaurant?->name, $booking->customer_name, $booking->number_of_guests, implode(', ', $booking->table_codes ?? []), $booking->status, $booking->note]);
            }
            fclose($stream);
        }, 'bao-cao-dat-ban-'.$from.'-'.$to.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
