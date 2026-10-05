<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    private const TABLE_CAPACITIES = [
        'B1' => 4,
        'B2' => 4,
        'B3' => 6,
        'B4' => 2,
        'B5' => 2,
        'B6' => 4,
    ];

    public function create(): View
    {
        $restaurants = Restaurant::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name', 'name_en']);

        $mustTryMenuItems = MenuItem::query()
            ->where('status', true)
            ->where('is_must_try', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'name_en', 'description', 'description_en', 'image', 'price']);

        $booking = null;

        if (session()->has('booking_success')) {
            $booking = Booking::query()
                ->with('restaurant')
                ->find(session('booking_id'));
        } else {
            session()->forget('booking_id');
        }

        $tableCapacities = self::TABLE_CAPACITIES;
        $maximumFloorCapacity = array_sum(self::TABLE_CAPACITIES);

        return view('fontend.bookings.create', compact('booking', 'maximumFloorCapacity', 'mustTryMenuItems', 'restaurants', 'tableCapacities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'integer', Rule::exists('restaurants', 'id')->where('status', true)],
            'floor' => ['required', 'integer', Rule::in([1, 2])],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'number_of_guests' => ['required', 'integer', 'between:1,100'],
            'table_codes' => ['nullable', 'array'],
            'table_codes.*' => ['string', 'distinct', Rule::in(array_keys(self::TABLE_CAPACITIES))],
            'note' => ['nullable', 'string', 'max:2000'],
            'pre_order_items' => ['nullable', 'array'],
            'pre_order_items.*' => [
                'integer',
                'distinct',
                Rule::exists('menu_items', 'id')
                    ->where('status', true)
                    ->where('is_must_try', true),
            ],
        ]);

        $tableCodes = $validated['table_codes'] ?? [];
        $tableCapacity = array_sum(array_map(
            fn (string $tableCode): int => self::TABLE_CAPACITIES[$tableCode],
            $tableCodes,
        ));
        $partySize = $validated['number_of_guests'];
        $maximumFloorCapacity = array_sum(self::TABLE_CAPACITIES);

        if ($partySize <= $maximumFloorCapacity && $tableCodes === []) {
            return back()
                ->withErrors(['table_codes' => __('Vui lòng chọn bàn phù hợp với số lượng người.')])
                ->withInput();
        }

        if ($tableCodes !== [] && $tableCapacity < $partySize) {
            return back()
                ->withErrors(['table_codes' => __('Tổng sức chứa bàn đã chọn chưa đủ số lượng người.')])
                ->withInput();
        }

        $preOrderItems = MenuItem::query()
            ->whereIn('id', $validated['pre_order_items'] ?? [])
            ->where('status', true)
            ->where('is_must_try', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'name_en'])
            ->map(function (MenuItem $menuItem): array {
                $preOrderItem = [
                    'id' => $menuItem->id,
                    'name' => $menuItem->name,
                ];

                if (filled($menuItem->name_en)) {
                    $preOrderItem['name_en'] = $menuItem->name_en;
                }

                return $preOrderItem;
            })
            ->all();

        unset($validated['pre_order_items'], $validated['table_codes']);

        $bookingCode = $this->generateBookingCode();

        $booking = Booking::create([
            ...$validated,
            'table_codes' => $tableCodes ?: null,
            'pre_order_items' => $preOrderItems ?: null,
            'booking_code' => $bookingCode,
            'qr_code' => $bookingCode,
            'status' => 'pending',
        ]);

        $request->session()->put('booking_id', $booking->id);

        return to_route('booking.create')
            ->with('booking_success', true)
            ->with('booking_code', $booking->booking_code);
    }

    public function cancel(Request $request): RedirectResponse
    {
        $bookingId = $request->session()->get('booking_id');

        abort_if($bookingId === null, 404);

        $booking = Booking::query()->findOrFail($bookingId);

        if (in_array($booking->status, ['pending', 'confirmed'], true)) {
            $booking->update(['status' => 'cancelled']);
        }

        $request->session()->forget('booking_id');

        return to_route('booking.create')
            ->with('booking_cancelled', true);
    }

    private function generateBookingCode(): string
    {
        do {
            $bookingCode = 'BK'.now()->format('YmdHis').Str::upper(Str::random(4));
        } while (Booking::query()->where('booking_code', $bookingCode)->exists());

        return $bookingCode;
    }
}
