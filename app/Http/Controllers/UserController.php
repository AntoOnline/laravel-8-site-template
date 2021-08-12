<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EventType;
use App\Mail\web\user\Register;
use App\Rules\captchaValid;
use Illuminate\Http\Request;
use App\Mail\web\user\SetPassword;
use Illuminate\Support\Carbon;
use App\Mail\web\user\DeleteAccount;
use App\Mail\web\user\ChangePassword;
use App\Mail\web\user\ForgotPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'recaptcha' => ['required', new captchaValid]
        ]);

        $data = $request->all();
        $user = new User;
        $user->name = ucfirst($data['name']);
        $user->email = $data['email'];
        $user->password = Hash::make(rand()); // generate a random number as password

        if ($user->save()) {
            $this->event(EventType::REGISTRATION, "", $user->id);

            $token = $this->makeToken($user->email);
            $url = route('web.set_password', ['token' => $token]);

            Mail::to($user)->send(new Register($user->name, $url) );

            return view('shared.info', [
                'head' => 'Email confirmation link sent!',
                'message' => 'You will receive a registration link in your provided email address.'
            ]);
        } else {
            return view('shared.info', [
                'head' => 'Unexpected Error!',
                'message' => 'Error occoured while creating user. Please try later.'
            ]);
        }
    }
    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|max:32|confirmed',
            'password_token' => 'required',
            'recaptcha' => [new captchaValid]
        ]);

        $password = $request->password;
        $token = $request->has('password_token') ? $request->password_token : null;

        if (false === $this->tokenValid($token)) {
            return back()->withErrors(['error', 'Unkown Error! Please try later. ']);
        }

        $user = $this->getUserFromResetToken($token);
        $user->password = Hash::make($password);
        $user->email_verified_at = Carbon::now();

        if ($user->save()) {
            $this->event(EventType::REGISTRATION_CONFIRMED, "", $user->id);
            $this->deleteToken($token);

            Mail::to($user)->send(new SetPassword($user->name));

            return view('shared.info', [
                'head' => 'Password Changed!',
                'message' => 'Password Changed Successfully! You can now login.'
            ]);
        } else {
            return view('shared.info', [
                'head' => 'Unkown Error!',
                'message' => 'An unkown error occured. Please try later.'
            ]);
        };
    }

    public function settings(Request $request)
    {
        $settings = $request->has('settings') && is_array($request->settings)
            ?  $request->settings
            : [];

        $user = Auth::user();
        $user->preferences = json_encode($settings);

        if ($user->save()) {
            $this->event(EventType::SETTINGS_SAVED, $user->preferences);

            $success = "Settings saved.";

            return view('web.admin.settings')->with(compact('success', 'settings'));
        } else {
            $faliure = 'Settings not saved. Try again later.';

            return view('web.admin.settings')->with(compact('faliure', 'settings'));
        }
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => "required|password"
        ]);

        $id = Auth::user()->id;

        $user = User::find($id);
        $email = $user->email;
        $name = $user->name;

        if ($user->delete()) {
            $this->event(EventType::ACCOUNT_DELETED, "", $id);
            $this->deleteTokensForEmail($email);
            Auth::logout();
            Session::flush();

            Mail::to($email)->send(new DeleteAccount($name));

            return view('shared.info', [
                'head' => 'Account Deleted',
                'message' => 'Your account has been deleted.'
            ]);
        } else {
            return view('shared.info', [
                'head' => 'Account Deletion Failed',
                'message' => 'Failed to delete your account, please try later.'
            ]);
        }
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => "required|password",
            'password' => 'required|confirmed|min:6|max:32',
            'recaptcha' => ['required', new captchaValid]
        ]);

        $password = $request->password;
        $user = Auth::user();
        $user->password = Hash::make($password);

        if ($user->save()) {
            $this->event(EventType::PASSWORD_RESET);

            // After password change, the user must log back in
            $this->event(EventType::LOG_OUT);

            Session::flush();
            Auth::logout();

            Mail::to($user)->send(new ChangePassword($user->name));

            return view('shared.info')
                ->with('head', 'Password Changed!')
                ->with('message', 'Password Changed Successfully! Please login again.');
        };
    }
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where(['email' => $request->email])->first();

        $token = $this->makeToken($user->email);
        $link = route('web.set_password', ['token' => $token]);

        if ($token) {
            $this->event(EventType::PASSWORD_RESET_REQUESTED);
        }

        Mail::to($user)->send(new ForgotPassword($user->name, $link));

        return view('shared.info', [
            'head' => 'Reset Link Sent',
            'message' => 'A Password Reset link has been sent to your email address. '
        ]);
    }
}
