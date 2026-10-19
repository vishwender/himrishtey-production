<?php

namespace App\Website\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details, public array $siteSettings)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->mailer($this->siteSettings['mailer'])
            ->from($this->siteSettings['address'], $this->siteSettings['name'])
            ->subject('Welcome to '.$this->siteSettings['brand'])
            ->view('emails.registeremail')->with($this->details + [
                'brand' => $this->siteSettings['brand'],
                'supportAddress' => $this->siteSettings['support_address'],
                'siteUrl' => $this->siteSettings['url'],
            ]);
    }
}
