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

class BookingController extends Controller
{
    public function availability(Request $request, BookingService $bookingService): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
        ]);

        return response()->json([
            'unavailable' => $bookingService->unavailableTables(
                $validated['restaurant_id'], $validated['booking_date'], $validated['booking_time']
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

        $tableCapacities = $bookingService->tableCapacities();
        $tableFloors = $bookingService->tableFloors();
        $floorCapacities = [];
        foreach ($tableCapacities as $code => $capacity) {
            $floorCapacities[$tableFloors[$code]] = ($floorCapacities[$tableFloors[$code]] ?? 0) + $capacity;
        }
        $maximumFloorCapacity = max($floorCapacities);
        $unavailableTables = $bookingService->unavailableTables(
            old('restaurant_id'), old('booking_date'), old('booking_time')
        );

        return view('frontend.bookings.create', compact('booking', 'maximumFloorCapacity', 'mustTryMenuItems', 'restaurants', 'tableCapacities', 'tableFloors', 'unavailableTables'));
    }

    public function store(BookingRequest $request, BookingService $bookingService, SettingService $settings): RedirectResponse
    {
        $validated = $request->validated();

        $tableCapacities = $bookingService->tableCapacities();
        $tableFloors = $bookingService->tableFloors();
        $floorCapacities = [];
        foreach ($tableCapacities as $code => $capacity) {
            $floorCapacities[$tableFloors[$code]] = ($floorCapacities[$tableFloors[$code]] ?? 0) + $capacity;
        }
        $maximumFloorCapacity = max($floorCapacities);

        if ($error = $bookingService->tableSelectionError($validated['number_of_guests'], $validated['table_codes'] ?? [], $validated['floor'])) {
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
