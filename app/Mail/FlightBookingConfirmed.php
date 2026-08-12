<?php

namespace App\Mail;

use App\FlightBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightBookingConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public FlightBooking $booking;

    public function __construct(FlightBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this
            ->subject('Your Flight Booking is Confirmed – Travelia')
            ->view('emails.flights.confirmed')
            ->with('booking', $this->booking);
    }
}
