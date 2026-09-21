<?php

namespace App\Website\Mail;

use App\Website\Models\ContactMessage;
use Illuminate\Mail\Mailable;

class ContactInquiry extends Mailable
{
    public function __construct(public ContactMessage $inquiry) {}

    public function build(): static
    {
        return $this->subject('Contact inquiry #'.$this->inquiry->id.': '.$this->inquiry->subject)
            ->replyTo($this->inquiry->email, $this->inquiry->name)
            ->view('emails.contact-inquiry');
    }
}
