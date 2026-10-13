<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Mail\BookingConfirmation;
use App\Services\SettingService;
use App\Models\Booking;

class BookingController extends Controller
{
    public function myBookings(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with('restaurant')
            ->latest('booking_date')
            ->latest('booking_time')
            ->paginate(10);

        return view('frontend.bookings.index', compact('bookings'));
    }

    public function lookup(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'booking_code' => ['required', 'string', 'max:32'],
                'contact' => ['required', 'string', 'max:255'],
            ]);

            $booking = Booking::query()->with('restaurant')
                ->where('booking_code', strtoupper(trim($data['booking_code'])))
                ->where(fn ($query) => $query->where('email', $data['contact'])->orWhere('phone', $data['contact']))
                ->first();

            if (! $booking) {
                return back()->withInput()->withErrors(['booking_code' => __('Không tìm thấy đặt bàn. Vui lòng kiểm tra mã đặt bàn và email hoặc số điện thoại đã dùng khi đặt.')]);
            }

            $request->session()->put('looked_up_booking_id', $booking->id);

            return to_route('booking.lookup.result');
        }

        return view('frontend.bookings.lookup', ['booking' => null]);
    }

    public function lookupResult(Request $request): View
    {
        $bookingId = $request->session()->get('looked_up_booking_id');
        abort_if(! $bookingId, 404);
        $booking = Booking::query()->with('restaurant')->findOrFail($bookingId);

        return view('frontend.bookings.lookup', compact('booking'));
    }

    public function cancelManaged(Request $request, BookingService $bookingService, Booking $booking): RedirectResponse
    {
        $isOwner = $request->user() && $booking->user_id === $request->user()->id;
        $isLookupOwner = (int) $request->session()->get('looked_up_booking_id') === $booking->id;
        abort_unless($isOwner || $isLookupOwner, 404);

        $bookingService->cancelIfAllowed($booking);

        return $request->user()
            ? to_route('booking.mine')->with('booking_cancelled', true)
            : to_route('booking.lookup.result')->with('booking_cancelled', true);
    }

    public function availability(Request $request, BookingService $bookingService): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
            'booking_date' => ['nullable', 'date', 'after_or_equal:today'],
            'booking_time' => ['nullable', 'date_format:H:i'],
            'exclude_booking_id' => ['nullable', 'integer', 'exists:bookings,id'],
        ]);
        $validated['booking_date'] ??= null;
        $validated['booking_time'] ??= null;

        return response()->json([
            'tables' => $bookingService->tablesForRestaurant((int) $validated['restaurant_id'])->map(fn ($table) => [
                'code' => $table->code, 'floor' => $table->floor, 'capacity' => $table->capacity,
                'position' => $table->position,
            ])->values(),
            'unavailable' => $bookingService->unavailableTables(
                $validated['restaurant_id'], $validated['booking_date'], $validated['booking_time'], $validated['exclude_booking_id'] ?? null
            ),
        ]);
    }

    public function create(BookingService $bookingService): View
    {
        $restaurants = $bookingService->formRestaurants();
        $mustTryMenuItems = $bookingService->mustTryMenuItems();

        $booking = null;

        if (session()->has('booking_success')) {
            $booking = $bookingService->findWithRestaurant((int) session('booking_id'));
        } else {
            session()->forget('booking_id');
        }

        $selectedRestaurantId = old('restaurant_id') ?: $restaurants->first()?->id;
        $tables = $bookingService->tablesForRestaurant($selectedRestaurantId);
        $tableCapacities = $tables->pluck('capacity', 'code')->all();
        $tableFloors = $tables->pluck('floor', 'code')->all();
        $floorCapacities = [];
        foreach ($tableCapacities as $code => $capacity) {
            $floorCapacities[$tableFloors[$code]] = ($floorCapacities[$tableFloors[$code]] ?? 0) + $capacity;
        }
        $maximumFloorCapacity = (int) ($floorCapacities ? max($floorCapacities) : 0);
        $unavailableTables = $bookingService->unavailableTables(
            $selectedRestaurantId, old('booking_date'), old('booking_time')
        );

        return view('frontend.bookings.create', compact('booking', 'maximumFloorCapacity', 'mustTryMenuItems', 'restaurants', 'tableCapacities', 'tableFloors', 'unavailableTables'));
    }

    public function store(BookingRequest $request, BookingService $bookingService, SettingService $settings): RedirectResponse
    {
        $validated = $request->validated();

        if ($error = $bookingService->tableSelectionError($validated['number_of_guests'], $validated['table_codes'] ?? [], $validated['floor'], $validated['restaurant_id'])) {
            throw ValidationException::withMessages(['table_codes' => $error]);
        }

        $unavailableTables = $bookingService->unavailableTables(
            $validated['restaurant_id'], $validated['booking_date'], $validated['booking_time']
        );
        if (array_intersect($validated['table_codes'] ?? [], $unavailableTables)) {
            throw ValidationException::withMessages(['table_codes' => __('Một hoặc nhiều bàn vừa được khách khác đặt. Vui lòng chọn lại bàn.')]);
        }

        $booking = $bookingService->create($validated);

        $adminEmail = config('booking.admin_email') ?: $settings->get('email');
        $this->sendBookingEmail($booking, $booking->email, false);
        $this->sendBookingEmail($booking, $adminEmail, true);

        $request->session()->put('booking_id', $booking->id);

        return to_route('booking.create')
            ->with('booking_success', true)
            ->with('booking_code', $booking->booking_code);
    }

    private function sendBookingEmail(\App\Models\Booking $booking, ?string $recipient, bool $isAdmin): void
    {
        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            Mail::to($recipient)->send(new BookingConfirmation($booking->load('restaurant'), $isAdmin));
        } catch (\Throwable $exception) {
            Log::error('Could not send booking notification email.', [
                'booking_id' => $booking->id,
                'recipient' => $recipient,
                'is_admin' => $isAdmin,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    public function cancel(Request $request, BookingService $bookingService): RedirectResponse
    {
        $bookingId = $request->session()->get('booking_id');

        abort_if($bookingId === null, 404);

        $bookingService->cancelIfAllowed($bookingService->find((int) $bookingId));

        $request->session()->forget('booking_id');

        return to_route('booking.create')
            ->with('booking_cancelled', true);
    }
}
