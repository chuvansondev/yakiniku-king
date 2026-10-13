<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $criteria = $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $customers = Customer::query()
            ->withCount(['bookings', 'leads'])
            ->when(filled($criteria['search'] ?? null), function ($query) use ($criteria): void {
                $term = trim($criteria['search']);
                $query->where(fn ($search) => $search
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%"));
            })
            ->when(isset($criteria['status']), fn ($query) => $query->where('status', $criteria['status']))
            ->orderByDesc('last_contact_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer): View
    {
        $customer->loadCount(['bookings', 'leads']);
        $bookings = $customer->bookings()->with('restaurant')->latest('booking_date')->latest('booking_time')->paginate(10, ['*'], 'bookings_page');
        $leads = $customer->leads()->latest()->paginate(10, ['*'], 'leads_page');

        return view('admin.customers.show', compact('customer', 'bookings', 'leads'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $email = filled($data['email'] ?? null) ? trim($data['email']) : null;
        $phone = filled($data['phone'] ?? null) ? trim($data['phone']) : null;
        $customer->update([
            ...$data,
            'email' => $email,
            'email_normalized' => $email ? mb_strtolower($email) : null,
            'phone' => $phone,
            'phone_normalized' => $phone ? (preg_replace('/\D+/', '', $phone) ?: null) : null,
        ]);

        return to_route('admin.customers.show', $customer)->with('success', __('Customer profile updated.'));
    }
}
