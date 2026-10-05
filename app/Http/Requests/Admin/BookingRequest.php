<?php

namespace App\Http\Requests\Admin;

use App\Enums\BookingStatus;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'restaurant_id' => ['required', 'exists:restaurants,id'], 'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'], 'email' => ['nullable', 'email', 'max:255'],
            'booking_date' => ['required', 'date'], 'booking_time' => ['required', 'date_format:H:i'],
            'number_of_guests' => ['required', 'integer', 'min:1', 'max:100'], 'note' => ['nullable', 'string'],
            'status' => ['required', 'in:'.implode(',', array_map(fn (BookingStatus $status): string => $status->value, BookingStatus::cases()))],
        ];
    }
}
