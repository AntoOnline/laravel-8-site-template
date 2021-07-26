<?php

namespace App\Console\Commands;

use App\Mail\commands\SendPasswordTokenReminders\AccountDeleted;
use App\Mail\commands\SendPasswordTokenReminders\Reminder15Mins;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendPasswordTokenReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendPasswordTokenReminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email reminders to newly registered users.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = "
                select u.id as id,
                u.name as name,
                u.email as email,
                u.email_verified_at as email_verified_at,
                pr.token as token,
                pr.created_at as created_at,
                pr.last_reminder as last_reminder
                from password_resets as pr
                inner join users as u
                on pr.email = u.email
                where email_verified_at is null
            ";
        $records = DB::select($query);

        foreach ($records as $rec) {
            $time = Carbon::parse($rec->created_at);
            $now = Carbon::now();

            $email = $rec->email;
            $name = $rec->name;
            $token = $rec->token;
            $link = route('web.set_password', [$token]);

            if (!$rec->last_reminder && $time->addMinutes(15) < $now) {
                // 15 minutes have passed and the person has not yet met reminded.
                // send a reminder email

                Mail::to($email)->send(new Reminder15Mins($name, $link));

                DB::update('update password_resets set last_reminder = "15_mins" where token = ? and email = ?', [$token, $email]);
            } elseif ($rec->last_reminder === '15_mins' && $time->addHours(12) < $now) {
                // 12 hourse have passed and the person has not yet met reminded again.
                // send a reminder email
                $link = route('web.set_password', [$token]);

                Mail::to($email)->send(new Reminder15Mins($name, $link));

                DB::update('update password_resets set last_reminder = "12_hours" where token = ? and email = ?', [$token, $email]);
            } elseif ($rec->last_reminder === '12_hours' && $time->addHours(24) < $now) {
                //User has not verified the account and 24 hours have passed. Deleting account now.
                if (User::find($rec->id)->delete()) {
                    DB::delete('delete from password_resets where email = ?', [$email]);

                    Mail::to($email)->send(new AccountDeleted($name));
                }
            }
        }

        $query = "
            select u.email as email,
                u.email_verified_at as email_verified_at,
                pr.created_at as created_at
            from password_resets as pr
            inner join users as u
            on pr.email = u.email
            where email_verified_at is not null
            ";
        $records = DB::select($query);
        foreach ($records as $rec) {
            $time = Carbon::parse($rec->created_at);
            $now = Carbon::now();

            $email = $rec->email;
            if ($time->addMinutes(30) < $now) {
                //User has reset account password. Deleting token now.
                DB::delete('delete from password_resets where email = ?', [$email]);
            }
        }
        echo "1";
    }
}
