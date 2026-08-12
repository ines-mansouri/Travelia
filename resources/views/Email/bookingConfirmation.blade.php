@component('mail::message')
# Booking Confirmed!

Hi **{{ $booking->user->name ?? 'Traveler' }}**,

Your booking has been confirmed! Here are the details:

**Destination:** {{ $booking->destination->title ?? 'N/A' }}
**Travel Date:** {{ $booking->travel_date ? $booking->travel_date->format('M d, Y') : 'TBD' }}
**Guests:** {{ $booking->guests }}
**Total:** ${{ number_format($booking->total_price, 2) }}
**Status:** {{ ucfirst($booking->status) }}

@component('mail::button', ['url' => route('dashboard')])
View My Bookings
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
