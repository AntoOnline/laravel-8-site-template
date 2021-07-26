<?php

namespace App\Mail\commands\SendPasswordTokenReminders;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reminder15Mins extends Mailable
{
    use Queueable, SerializesModels;
    public $link;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $link)
    {
        $this->link = $link;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = config('app.name') . ": Registration Reminder";

        return $this
            ->view('emails.commands.SendPasswordTokenReminders.reminder_15_mins')
            ->text('emails.commands.SendPasswordTokenReminders.reminder_15_mins_alt')
            ->subject($subject);
    }
}
