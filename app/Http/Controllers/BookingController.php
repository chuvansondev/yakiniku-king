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
use App\Mail\BookingConfirmation;
use App\Services\SettingService;

class BookingController extends Controller
{
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
        $maximumFloorCapacity = array_sum($tableCapacities);

        return view('frontend.bookings.create', compact('booking', 'maximumFloorCapacity', 'mustTryMenuItems', 'restaurants', 'tableCapacities'));
    }

    public function store(BookingRequest $request, BookingService $bookingService, SettingService $settings): RedirectResponse
    {
        $validated = $request->validated();

        $tableCapacities = $bookingService->tableCapacities();
        $maximumFloorCapacity = array_sum($tableCapacities);

        if ($error = $bookingService->tableSelectionError($validated['number_of_guests'], $validated['table_codes'] ?? [])) {
            throw ValidationException::withMessages(['table_codes' => $error]);
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
