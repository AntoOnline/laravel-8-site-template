<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function index(Request $request)
    {
        return view('web.index');
    }

    public function login(Request $request)
    {
        return view('web.login');
    }

    public function register(Request $request)
    {
        return view('web.register');
    }

    public function forgotPassword(Request $request)
    {
        return view('web.forgot_password');
    }

    public function setPassword(Request $request)
    {
        if (
            false === $request->has('token')
            || false === $this->tokenValid($request->token)
        ) {
            $head = 'Invalid Link';
            $message = 'The provided link is invlaid or expired. Please try again.';
            return view('shared.info')
                ->with(compact('head', 'message'));
        }
        return view('web.set_password')
            ->with('password_token', $request->token);
    }
}
