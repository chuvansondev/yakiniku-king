@extends('admin.layouts.app')

@section('title', __('Äáº·t bÃ n'))
@section('page-title', __('Äáº·t bÃ n'))

@section('content')
@include('admin.menu.partials.list-styles')
@include('admin.menu.partials.list-controls', ['listModel' => App\Models\Booking::class, 'listRoute' => route('admin.menu.bookings.index'), 'filterOptions' => $filterOptions ?? []])

@if(session('success'))
    <div class="admin-notice" role="status">{{ session('success') }}</div>
@endif

<div class="admin-page-heading">
    <h1>{{ __('Quáº£n lÃ½ Ä‘áº·t bÃ n') }}</h1>
    <a href="{{ route('admin.menu.bookings.create') }}"
    style="
            background:#111;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        "
    >
        + {{ __('ThÃªm booking') }}
    </a>

</div>


<div class="admin-table-wrap"><table
    border="1"
    cellpadding="10"
    cellspacing="0"
    width="100%"
>

    <thead>

        <tr>
            <th>{{ __('MÃ£ booking') }}</th>
            <th>{{ __('NhÃ  hÃ ng') }}</th>
            <th>{{ __('Táº§ng') }}</th>
            <th>{{ __('BÃ n') }}</th>
            <th>{{ __('KhÃ¡ch hÃ ng') }}</th>
            <th>{{ __('SÄT') }}</th>
            <th>{{ __('NgÃ y') }}</th>
            <th>{{ __('Giá»') }}</th>
            <th>{{ __('Sá»‘ ngÆ°á»i') }}</th>
            <th>{{ __('MÃ³n gá»i trÆ°á»›c') }}</th>
            <th>{{ __('Tráº¡ng thÃ¡i') }}</th>
            <th>{{ __('Thao tÃ¡c') }}</th>
        </tr>

    </thead>


    <tbody>

        @forelse($bookings as $booking)

            <tr>

                <td>
                    {{ $booking->booking_code }}
                </td>


                <td>
                    {{ $booking->restaurant->name ?? '-' }}
                </td>

                <td>
                    {{ $booking->floor ? __('Táº§ng').' '.$booking->floor : '-' }}
                </td>

                <td>
                    {{ $booking->table_codes ? implode(', ', $booking->table_codes) : __('NhÃ  hÃ ng bá»‘ trÃ­') }}
                </td>


                <td>
                    {{ $booking->customer_name }}
                </td>


                <td>
                    {{ $booking->phone }}
                </td>


                <td>
                    {{ $booking->booking_date->format('d/m/Y') }}
                </td>


                <td>
                    {{ substr($booking->booking_time, 0, 5) }}
                </td>


                <td>
                    {{ $booking->number_of_guests }}
                </td>

                <td>
                    {{ collect($booking->pre_order_items ?? [])->pluck('name')->join(', ') ?: '-' }}
                </td>


                <td>

                    @switch($booking->status)

                        @case(\App\Enums\BookingStatus::Pending->value)
                            {{ __('Chá» xÃ¡c nháº­n') }}
                            @break

                        @case(\App\Enums\BookingStatus::Confirmed->value)
                            {{ __('ÄÃ£ xÃ¡c nháº­n') }}
                            @break

                        @case(\App\Enums\BookingStatus::Cancelled->value)
                            {{ __('ÄÃ£ há»§y') }}
                            @break

                        @case(\App\Enums\BookingStatus::Completed->value)
                            {{ __('ÄÃ£ hoÃ n thÃ nh') }}
                            @break

                    @endswitch

                </td>


                <td>

                    <a href="{{ route('admin.menu.bookings.edit', $booking) }}">
                        {{ __('Sá»­a') }}
                    </a>


                    <form
                        action="{{ route('admin.menu.bookings.destroy', $booking) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm(@json(__('Báº¡n cÃ³ cháº¯c muá»‘n xÃ³a booking nÃ y?')))"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            {{ __('XÃ³a') }}
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="12">
                    {{ __('ChÆ°a cÃ³ booking nÃ o.') }}
                </td>

            </tr>

        @endforelse

    </tbody>

</table></div>

@include('admin.menu.partials.pagination', ['paginator' => $bookings])

@endsection
