<?php

namespace App\Mail\user;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $email_confirmation_link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name, string $email_confirmation_link)
    {
        $this->name = $name;
        $this->email_confirmation_link = $email_confirmation_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = config('app.name') . ": Registration Email";

        $this->subject($subject)
            ->view('emails.web.user.register')
            ->text('emails.web.user.register_alt');

        return $this;
    }
}
