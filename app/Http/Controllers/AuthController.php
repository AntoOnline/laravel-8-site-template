<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use App\Rules\captchaValid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Controls authentication functionality
 */
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'recaptcha' => ['required', new captchaValid]
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember_me');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Login successful, log event
            $this->event(EventType::LOG_IN);

            return redirect()->route('web.admin.index');
        }
        return back()->withErrors([
            'error' => 'Login failed! Please recheck your credentials.'
        ]);
    }
    /**
     * User logout function
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $this->event(EventType::LOG_OUT);
        $request->session()->flush();
        Auth::logout();
        return redirect()->route('web.login');
    }
}
