<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailDemo extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct(array $mailData = [])
    {
        $this->mailData = $mailData;
    }

    public function build()
    {
        return $this->markdown('Email.demoEmail')
            ->subject($this->mailData['subject'] ?? 'Travelia Notification')
            ->with('mailData', $this->mailData);
    }
}
