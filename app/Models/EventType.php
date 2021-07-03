<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{

    use HasFactory;
    const LOG_IN = 1;
    const LOG_OUT = 2;
    const REGISTRATION = 3;
    const REGISTRATION_CONFIRMED = 4;
    const PASSWORD_RESET_REQUESTED = 5;
    const PASSWORD_RESET = 6;
    const EMAIL_SENT = 7;
    const SETTINGS_SAVED = 8;
    const ACCOUNT_DELETED = 9;
    const GENERIC = 10;

    public function events(){
        return $this->hasMany(Event::class);
    }
}
