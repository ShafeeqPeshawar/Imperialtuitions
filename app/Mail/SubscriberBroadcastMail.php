<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberBroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;    // ← add this
    public $content; // ← add this

    public function __construct($name, $content)
    {
        $this->name = $name;
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject('Message from Imperial Tuitions')
                    ->view('emails.subscriber-message');
    }
}