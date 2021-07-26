<?php

namespace App\Mail\commands\SendPasswordTokenReminders;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = config('app.name') . ": Acoount Deleted";

        return $this
            ->view('emails.commands.SendPasswordTokenReminders.account_deleted')
            ->text('emails.commands.SendPasswordTokenReminders.account_deleted_alt')
            ->subject($subject);
    }
}
