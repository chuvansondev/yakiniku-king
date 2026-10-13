<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\AdminResourceService;
use App\Services\BookingService;
use App\Models\Restaurant;
use App\Http\Requests\Admin\BookingRequest;

class BookingController extends Controller
{
    public function index(\Illuminate\Http\Request $request, AdminResourceService $resources)
    {
        $bookings = $resources->paginate(Booking::class, $request, with: ['restaurant'], orderBy: [['id', 'desc']]);
        $filterOptions = $resources->listFilterOptions(Booking::class);

        return view('admin.menu.bookings.index', compact('bookings', 'filterOptions'));
    }

    public function create(AdminResourceService $resources)
    {
        $restaurants = $resources->all(Restaurant::class, orderBy: [['name', 'asc']], filters: ['status' => true]);
        $tables = app(BookingService::class)->tablesForRestaurant(old('restaurant_id') ?: $restaurants->first()?->id);

        return view(
            'admin.menu.bookings.create',
            compact('restaurants', 'tables')
        );
    }

    public function store(BookingRequest $request, BookingService $bookingService, \App\Services\CustomerService $customers)
    {
        $validated = $request->validated();

        $bookingService->createAdminBooking($validated, $customers);

        return redirect()
            ->route('admin.menu.bookings.index')
            ->with('success', __('Thêm booking thành công.'));
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
        $booking->load('statusHistory');
        $restaurants = $resources->all(Restaurant::class, orderBy: [['name', 'asc']]);
        $tables = app(BookingService::class)->tablesForRestaurant(old('restaurant_id', $booking->restaurant_id));

        return view(
            'admin.menu.bookings.edit',
            compact('booking', 'restaurants', 'tables')
        );
    }

    public function update(BookingRequest $request, Booking $booking, BookingService $bookingService, \App\Services\CustomerService $customers)
    {
        $validated = $request->validated();
        $bookingService->updateAdminBooking($booking, $validated, $customers);

        return redirect()
            ->route('admin.menu.bookings.index')
            ->with('success', __('Cập nhật booking thành công.'));
    }

    public function destroy(Booking $booking, AdminResourceService $resources)
    {
        $resources->delete($booking);

        return redirect()
            ->route('admin.menu.bookings.index')
            ->with('success', __('Xóa booking thành công.'));
    }

}
