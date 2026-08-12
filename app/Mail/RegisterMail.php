<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $companyName;
    public $userName;
    public $url;

    public function __construct($companyName, $userName, $url)
    {
        $this->companyName = $companyName;
        $this->userName = $userName;
        $this->url = $url;
    }

    public function build()
    {
        return $this
            ->subject('Registration Successful')
            ->html(
                "<p>Dear {$this->userName}, welcome to {$this->companyName}! Your registration is successful. Get ready for an amazing journey filled with trust, quality, and convenience.<br>{$this->companyName}<br>{$this->url}</p>"
            );
    }
}
