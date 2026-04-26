<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

   public $name;

public function __construct($name)
{
    $this->name = $name;
}

   public function build()
{
    return $this
        ->subject('Welcome to Imperial Tuitions  – You’re Subscribed!')
        ->view('emails.subscriber_welcome')
        ->with([
            'name' => $this->name,
        ]);
}
}