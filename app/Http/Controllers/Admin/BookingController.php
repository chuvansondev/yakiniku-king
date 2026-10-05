<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\AdminResourceService;
use App\Services\BookingService;
use App\Models\Restaurant;
use App\Http\Requests\Admin\BookingRequest as BookingFormRequest;

class BookingController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $bookings = $resources->all(Booking::class, with: ['restaurant'], orderBy: [['id', 'desc']]);

        return view('admin.menu.bookings.index', compact('bookings'));
    }

    public function create(AdminResourceService $resources)
    {
        $restaurants = $resources->all(Restaurant::class, orderBy: [['name', 'asc']], filters: ['status' => true]);

        return view(
            'admin.menu.bookings.create',
            compact('restaurants')
        );
    }

    public function store(BookingFormRequest $request, BookingService $bookingService)
    {
        $validated = $request->validated();

        $bookingService->createAdminBooking($validated);

        return redirect()
            ->route('admin.menu.bookings.index')
            ->with('success', 'Thêm booking thành công.');
    }

    public function show(Booking $booking)
    {
        return redirect()->route(
            'admin.menu.bookings.edit',
            $booking
        );
    }

    public function edit(Booking $booking, AdminResourceService $resources)
    {
        $restaurants = $resources->all(Restaurant::class, orderBy: [['name', 'asc']]);

        return view(
            'admin.menu.bookings.edit',
            compact('booking', 'restaurants')
        );
    }

    public function update(BookingFormRequest $request, Booking $booking, AdminResourceService $resources)
    {
        $validated = $request->validated();

        $resources->update($booking, $validated);

        return redirect()
            ->route('admin.menu.bookings.index')
            ->with('success', 'Cập nhật booking thành công.');
    }

    public function destroy(Booking $booking, AdminResourceService $resources)
    {
        $resources->delete($booking);

        return redirect()
            ->route('admin.menu.bookings.index')
            ->with('success', 'Xóa booking thành công.');
    }

}
