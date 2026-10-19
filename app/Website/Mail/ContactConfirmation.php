<?php

namespace App\Website\Mail;

use Illuminate\Mail\Mailable;

class ContactConfirmation extends Mailable
{
    public function __construct(public string $clientName, public string $enquirySubject, public string $messageText, public array $siteSettings) {}

    public function build(): static
    {
        return $this->mailer($this->siteSettings['mailer'])
            ->from($this->siteSettings['address'], $this->siteSettings['name'])
            ->replyTo($this->siteSettings['address'], $this->siteSettings['name'])
            ->subject("We've Received Your Message - ".$this->siteSettings['brand'])
            ->view('emails.contact-confirmation', [
                'brand' => $this->siteSettings['brand'],
                'supportAddress' => $this->siteSettings['address'],
                'siteUrl' => $this->siteSettings['url'],
                'name' => $this->clientName,
                'subject' => $this->enquirySubject,
                'messageText' => $this->messageText,
            ]);
    }
}
