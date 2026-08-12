<?php

namespace App\Mail;

use App\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->markdown('Email.bookingConfirmation')
            ->subject('Booking Confirmed - Travelia')
            ->with('booking', $this->booking);
    }
}
