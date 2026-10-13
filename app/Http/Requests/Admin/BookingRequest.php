<?php

namespace App\Http\Requests\Admin;

use App\Enums\BookingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Services\BookingService;

class BookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $restaurantId = $this->input('restaurant_id');
        $tableCodes = is_numeric($restaurantId)
            ? app(BookingService::class)->tableCapacities((int) $restaurantId)
            : [];
        $booking = $this->route('booking');
        $allowedCodes = array_values(array_unique([
            ...array_keys($tableCodes),
            ...($booking instanceof \App\Models\Booking ? ($booking->table_codes ?? []) : []),
        ]));

        return [
            'restaurant_id' => ['required', 'integer', Rule::exists('restaurants', 'id')->where('status', true)],
            'floor' => ['required', 'integer', 'min:1', 'max:20'],
            'table_codes' => ['nullable', 'array'],
            'table_codes.*' => ['string', 'distinct', Rule::in($allowedCodes)],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'], 'email' => ['nullable', 'email', 'max:255'],
            'booking_date' => ['required', 'date'], 'booking_time' => ['required', 'date_format:H:i'],
            'number_of_guests' => ['required', 'integer', 'min:1', 'max:100'], 'note' => ['nullable', 'string'],
            'status' => ['required', 'in:'.implode(',', array_map(fn (BookingStatus $status): string => $status->value, BookingStatus::cases()))],
        ];
    }
}
