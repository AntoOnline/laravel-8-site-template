<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use GuzzleHttp\Client;
use App\Rules\captchaValid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Rules\passwordReEnteredCorrectly;

class CustomAuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request)
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

            return redirect()->intended('login-welcome');
        }
        return back()->withErrors([
            'error' => 'Login failed! Please recheck your credentials.'
        ]);
    }

    public function loginWelcome()
    {
        return view('adminDashboard');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function changeUserPassword()
    {
        return view('user.change_user_password');
    }

    public function changeUserPasswordConfirmed(Request $request)
    {
        $request->validate([
            'old_password' => "required|password",
            'password' => 'required|confirmed|min:6|max:32'
        ]);

        $password = $request->password;
        $user = Auth::user();
        $user->password = Hash::make($password);
        $user->save();

        session()->flush();

        $view_data = view("email_templates.user.password_changed", [
            'name' => ucfirst($user->name),
            'siteName' => config('app.name'),
        ])->render();

        $alt = view("email_templates.user.password_changed_alt", [
            'name' => ucfirst($user->name),
            'siteName' => config('app.name'),
        ])->render();

        $this->fireGuzzle($user->email, "Password Changed", $view_data, $alt);

        return view('auth.new_password_result', [
            'message' => "Password Changed Successfully! Please login again."
        ]);
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'recaptcha' => ['required', new captchaValid]
        ]);

        $data = $request->all();
        $check = $this->createUser($data);

        if ($check) {
            $token = $this->make_token($request->email);

            $view_data = view('email_templates.registration.first', [
                'name' => ucfirst($request->name),
                'siteName' => config('app.name'),
                'emailConfirmationLink' => URL::to("/password-change/$token/")
            ])->render();
            $alt = view('email_templates.registration.first_alt', [
                'name' => ucfirst($request->name),
                'siteName' => config('app.name'),
                'emailConfirmationLink' => URL::to("/password-change/$token/")
            ])->render();

            $guzzleRes = $this->fireGuzzle($request->email, "Registration email", $view_data, $alt);

            return view('auth.reg_results', [
                'message' => 'You will receive a registration link in your provided email address.'
            ]);
        } else {
            return view('auth.reg_results', [
                'message' => 'Error occoured while creating user. Please try later.'
            ]);
        }
    }

    private function fireGuzzle($email, $subject, $view_data, $alt_data)
    {
        $alt_data = base64_encode($alt_data);
        $view_data = base64_encode($view_data);

        $data = [
            "secret-name" => "shared-test-at-thehost-guru",
            "to-address" => $email,
            "base64-body" => $view_data,
            "base64-alt-body" => $alt_data,
            "subject" => $subject
        ];

        $apiUrl = env("SBUS_API_URL") . "message-services/mail-send";
        $apiToken = env("SBUS_API_TOKEN");

        $client = new Client();

        $result = $client->post($apiUrl, [
            'headers' => ['x-auth-token' => $apiToken],
            'http_errors' => false,
            'verify' => false,
            'json' => $data,
        ]);

        $jsonStr = $result->getBody()->getContents();
        $jsonArr = json_decode($jsonStr, true);

        return $jsonArr;
    }

    private function make_token($email)
    {
        DB::delete('delete from password_resets where email = ?', [$email]);
        do {
            $token = base64_encode(Hash::make(Str::random(32)));
        } while (DB::table('password_resets')->where([
            'token' => $token
        ])->count() != 0);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        return $token;
    }

    private function createUser(array $data)
    {
        $pass = rand(); // generate a random number as password
        return User::create([
            'name' => ucfirst($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($pass)
        ]);
    }

    public function dashboard()
    {
        $data = tools::all();

        return view('home', compact('data'));
    }



    public function signOut(Request $request)
    {
        $request->session()->flush();
        Auth::logout();

        return Redirect('login');
    }

    public function customRegistrationConfirmation(Request $req)
    {
        if (!$req->apitoken) {
            return view('auth.new_password_result', [
                'message' => 'Invalid link. Please try again.'
            ]);
        }
        $token = $req->apitoken;

        // dd(DB::table('password_resets')->get()->first());

        // Verifying Token
        $res = DB::table('password_resets')->where([
            'token' => $token
        ]);
        if ($res->count() != 1) {
            return view('auth.new_password_result', [
                'message' => 'Invlid token. Please try again.'
            ]);
        }

        return view('auth.new_password', ['password_token' => $token]);
    }

    public function passwordChangePost(Request $req)
    {
        $req->validate([
            'password' => 'required|min:6|max:32|confirmed',
            'password_token' => 'required'
        ]);
        $password = $req->password;
        $token = $req->password_token;

        $res = DB::table('password_resets')->where([
            'token' => $token
        ]);
        $user = User::where('email', $res->first()->email)->first();
        $user->password = Hash::make($password);
        $user->email_verified_at = Carbon::now();
        $user->save();

        DB::delete('delete from password_resets where token = ?', [$token]);

        $view_data = view("email_templates.user.password_changed", [
            'name' => ucfirst($user->name),
            'siteName' => config('app.name'),
        ])->render();
        $alt = view("email_templates.user.password_changed_alt", [
            'name' => ucfirst($user->name),
            'siteName' => config('app.name'),
        ])->render();

        $this->fireGuzzle($user->email, "Password Changed", $view_data, $alt);

        return view('auth.new_password_result', [
            'message' => 'Password Changed Successfully! You can now login.'
        ]);
    }

    public function forgotPasswordView(Request $request)
    {
        return view('auth.reset_password');
    }

    public function forgotPasswordPost(Request $request)
    {

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where(['email' => $request->email])->first();

        if ($user === null) {
            return back()->withErrors([
                'error' => 'You will receive an email to reset your password, if the account.'
            ]);
        }

        $token = $this->make_token($user->email);

        $view_data = view('email_templates.user.forgot_password', [
            'name' => ucfirst($user->name),
            'siteName' => config('app.name'),
            'resetLink' => URL::to("/password-change/$token/")
        ])->render();

        $alt = view('email_templates.user.forgot_password_alt', [
            'name' => ucfirst($user->name),
            'siteName' => config('app.name'),
            'resetLink' => URL::to("/password-change/$token/")
        ])->render();

        $this->fireGuzzle($user->email, "Password Change Requested", $view_data, $alt);

        return view('auth.reset_password_results', [
            'message' => 'A Password Reset link has been sent to your email address. '
        ]);
    }

    public function sendEmailNotifications(Request $request)
    {
        // $records = DB::table("password_resets")->get();

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

            if (!$rec->last_reminder && $time->addMinutes(15) < $now) {
                // 15 minutes have passed and the person has not yet met reminded.
                // send a reminder email
                $view_data = view('email_templates.registration.reminder_15_mins', [
                    'name' => ucfirst($name),
                    'siteName' => config('app.name'),
                    'emailConfirmationLink' => URL::to("/password-change/{$rec->token}/")
                ])->render();
                $alt = view('email_templates.registration.reminder_15_mins_alt', [
                    'name' => ucfirst($name),
                    'siteName' => config('app.name'),
                    'emailConfirmationLink' => URL::to("/password-change/{$rec->token}/")
                ])->render();

                $res = $this->fireGuzzle($rec->email, "Confirm your email address", $view_data, $alt);
                if (!isset($res['errors'])) {
                    DB::update('update password_resets set last_reminder = "15_mins" where token = ? and email = ?', [$rec->token, $rec->email]);
                }
            } elseif ($rec->last_reminder === '15_mins' && $time->addHours(12) < $now) {
                // 12 hourse have passed and the person has not yet met reminded again.
                // send a reminder email
                $view_data = view('email_templates.registration.reminder_12_hours', [
                    'name' => ucfirst($name),
                    'siteName' => config('app.name'),
                    'emailConfirmationLink' => URL::to("/password-change/{$rec->token}/")
                ])->render();
                $alt = view('email_templates.registration.reminder_12_hours_alt', [
                    'name' => ucfirst($name),
                    'siteName' => config('app.name'),
                    'emailConfirmationLink' => URL::to("/password-change/{$rec->token}/")
                ])->render();

                $res = $this->fireGuzzle($rec->email, "Confirm your email address", $view_data, $alt);
                if (!isset($res['errors'])) {
                    DB::update('update password_resets set last_reminder = "12_hours" where token = ? and email = ?', [$rec->token, $rec->email]);
                }
            } elseif ($rec->last_reminder === '12_hours' && $time->addHours(24) < $now) {

                //User has not verified the account and 24 hours have passed. Deleting account now.
                if (User::find($rec->id)->delete()) {
                    DB::delete('delete from password_resets where email = ?', [$email]);
                    $view_data = view("email_templates.registration.account_deleted", [
                        'name' => ucfirst($name),
                        'siteName' => config('app.name'),
                    ])->render();
                    $alt = view("email_templates.registration.account_deleted_alt", [
                        'name' => ucfirst($name),
                        'siteName' => config('app.name'),
                    ])->render();

                    $this->fireGuzzle($email, "Account Deleted", $view_data, $alt);
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

    public function saveSettings(Request $r)
    {
        if ($r->settings && is_array($r->settings)) {
            $prefs = json_encode($r->settings);
        } else {
            $prefs = json_encode([]);
        }

        $user = Auth::user();
        $user->preferences = $prefs;
        $user->save();
        return redirect()->action([CustomAuthController::class, 'settings']);
    }

    public function settings(Request $r)
    {
        $settings = json_decode(Auth::user()->preferences);

        return view('user.settings', [
            'settings' => $settings
        ]);
    }

    public function deleteAccount(Request $request)
    {
        return view('user.delete_account');
    }

    public function deleteAccountConfirmed(Request $request)
    {

        $request->validate([
            'password' => ['required', new passwordReEnteredCorrectly()]
        ]);

        $id = Auth::user()->id;

        Auth::logout();
        Session::flush();

        $user = User::find($id);
        $email = $user->email;
        $name = $user->name;

        if ($user->delete()) {
            DB::delete('delete from password_resets where email = ?', [$email]);
            $view_data = view("email_templates.user.account_deleted", [
                'name' => ucfirst($name),
                'siteName' => config('app.name'),
            ])->render();
            $alt = view("email_templates.user.account_deleted_alt", [
                'name' => ucfirst($name),
                'siteName' => config('app.name'),
            ])->render();

            $this->fireGuzzle($email, "Account Deleted", $view_data, $alt);

            return view('user.delete_account_confirmed', ['deleted' => true]);
        } else {
            return view('user.delete_account_confirmed', ['error' => true]);
        }
    }

    public function eventLogView(Request $req)
    {
        return view('user.event_log');
    }
}
