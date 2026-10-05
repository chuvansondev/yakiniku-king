<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\LeadStatus;
use App\Enums\AppLocale;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Combo;
use App\Models\Lead;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\Restaurant;
use App\Services\AdminResourceService;

class DashboardController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $today = today();
        $weekStart = $today->copy()->subDays(6);

        $menuItemsCount = $resources->count(MenuItem::class);

        $combosCount = $resources->count(Combo::class);

        $promotionsCount = $resources->count(Promotion::class);

        $restaurantsCount = $resources->count(Restaurant::class);

        $bookingsCount = $resources->count(Booking::class);

        $todayBookingsCount = $resources->countByDate(Booking::class, 'booking_date', $today);

        $pendingBookingsCount = $resources->count(Booking::class, ['status' => BookingStatus::Pending->value]);

        $newLeadsCount = $resources->count(Lead::class, ['status' => LeadStatus::New->value]);

        $bookingCountsByDate = $resources->countsByDateRange(Booking::class, 'booking_date', $weekStart->toDateString(), $today->toDateString());

        $bookingTrend = collect(range(6, 0))->map(function (int $daysAgo) use ($today, $bookingCountsByDate): array {
            $date = $today->copy()->subDays($daysAgo);

            return [
                'date' => $date->toDateString(),
                'label' => $date->format('d/m'),
                'weekday' => $date->locale(AppLocale::Vietnamese->value)->isoFormat('dd'),
                'count' => (int) $bookingCountsByDate->get($date->toDateString(), 0),
            ];
        });

        $bookingTrendMax = max(1, (int) $bookingTrend->max('count'));

        $recentBookings = $resources->all(Booking::class, with: ['restaurant'], orderBy: [['id', 'desc']], limit: 5);

        $recentLeads = $resources->all(Lead::class, orderBy: [['created_at', 'desc']], filters: ['status' => LeadStatus::New->value], limit: 4);

        return view('admin.dashboard', compact(
            'menuItemsCount',
            'combosCount',
            'promotionsCount',
            'restaurantsCount',
            'bookingsCount',
            'todayBookingsCount',
            'pendingBookingsCount',
            'newLeadsCount',
            'recentBookings',
            'recentLeads',
            'bookingTrend',
            'bookingTrendMax'
        ));
    }
}
