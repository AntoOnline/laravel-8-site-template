<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Event logger function.
     *
     * @param EventType $type Type of event, as defiend in EventType class
     * @param string $content Content of the event if any
     * @param int $user_id  User id incase of event logged when user not logged in
     * @return void
     */
    protected function event($type, $content = null, $user_id = null)
    {
        if ($user_id == null && Auth::check()) {
            $user_id = Auth::id();
        }
        $event = new Event();
        $event->event_type_id = $type;
        $event->content = $content;
        $event->user_id = $user_id;
        $event->save();
    }
    /**
     * Makes a unique token from random 32 letter string and base_64 encoding
     *
     * @param [type] $email
     * @return void
     */
    protected function makeToken($email)
    {
        // Deleting old token if any
        DB::delete('delete from password_resets where email = ?', [$email]);

        do {
            $token = base64_encode(Hash::make(Str::random(32)));
        } while (DB::table('password_resets')->where([
            // Loop to make sure token does not already exist
            'token' => $token
        ])->count() != 0);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        return $token;
    }

    protected function tokenValid($token)
    {
        if (!$token)
            return false;

        $res = DB::table('password_resets')->where([
            'token' => $token
        ]);

        if ($res->count() != 1)
            return false;

        return true;
    }

    protected function deleteToken($token)
    {
        return DB::delete('delete from password_resets where token = ?', [$token]);
    }
    protected function deleteTokensForEmail($email)
    {
        return DB::delete('delete from password_resets where email = ?', [$email]);
    }

    protected function getUserFromResetToken($token)
    {
        $email = DB::table('password_resets')
            ->where('token', '=',  $token)
            ->first()->email;
        return User::where('email', $email)->first();
    }
}
