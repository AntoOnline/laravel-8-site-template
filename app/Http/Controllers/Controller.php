<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function event($type, $content = null, $user_id = null){
        if($user_id == null && Auth::check()){
            $user_id = Auth::id();
        }
        $event = new Event();
        $event->event_type_id = $type;
        $event->content = $content;
        $event->user_id = $user_id;
        $event->save();
    }
}
