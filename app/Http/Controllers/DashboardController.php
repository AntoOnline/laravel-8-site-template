<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('web.admin.index');
    }
    public function settings(Request $request)
    {
        $settings = Auth::user()->preferences;
        $settings = json_decode($settings);
        return view('web.admin.settings')->with('settings', $settings);
    }
    public function deleteAccount(Request $request)
    {
        return view('web.admin.delete_account');
    }
    public function changePassword(Request $request)
    {
        return view('web.admin.change_password');
    }
    public function eventLog(Request $request)
    {
        $events = Event::where(['user_id' => Auth::id()])->orderBy('created_at', 'desc')->simplePaginate(25);;

        return view('web.admin.event_log')->with('events', $events);
    }
}
