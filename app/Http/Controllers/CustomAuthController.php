<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Models\EventType;
use App\Rules\captchaValid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Rules\passwordReEnteredCorrectly;


class CustomAuthController extends Controller
{
    /**
     * Guest login screen
     *
     * @return void
     */
    public function index()
    {
        if(!Auth::check()) // User not logged in, show login form
            return view('auth.login');

        // No else because loggin in user should not have access to this route
    }

    /**
     * Post method for login. Checks if the credentials are valid.
     *
     * @param Request $request
     * @return void
     */
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

            // Login successful, log event
            $this->event(EventType::LOG_IN);

            return redirect()->route('welcome');
        }
        return back()->withErrors([
            'error' => 'Login failed! Please recheck your credentials.'
        ]);
    }

    /**
     * Welcome screen for logged in user
     *
     * @return void
     */
    public function welcome()
    {
        return view('adminDashboard');
    }

    /**
     * Registration screen for guest users
     *
     * @return View
     */
    public function registration()
    {
        return view('auth.registration');
    }

    /**
     * Chance password screen for logged in users
     *
     * @return View
     */
    public function changeUserPassword()
    {
        return view('user.change_user_password');
    }

    /**
     * Confirm change of password. Validates old password and sets new password.
     *
     * @param Request $request
     * @return void
     */
    public function changeUserPasswordConfirmed(Request $request)
    {
        $request->validate([
            'old_password' => "required|password",
            'password' => 'required|confirmed|min:6|max:32',
            'recaptcha' => ['required', new captchaValid]
        ]);

        $password = $request->password;
        $user = Auth::user();
        $user->password = Hash::make($password);
        if($user->save()){
            $this->event(EventType::PASSWORD_RESET);
        };

        // After password change, the user must log back in
        $this->event(EventType::LOG_OUT);
        $request->session()->flush();
        Auth::logout();

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
            $this->event(EventType::REGISTRATION, "", $check->id);
            $token = $this->makeToken($request->email);

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

            $this->fireGuzzle($request->email, "Registration email", $view_data, $alt);

            return view('auth.reg_results', [
                'message' => 'You will receive a registration link in your provided email address.'
            ]);
        } else {
            return view('auth.reg_results', [
                'message' => 'Error occoured while creating user. Please try later.'
            ]);
        }
    }

    /**
     * Sends an email based on the input
     *
     * @param string $email To email
     * @param string $subject Subject of email
     * @param string $view_data The html data to show
     * @param string $alt_data The alternative text to show
     * @return void
     */
    private function fireGuzzle($email, $subject, $view_data, $alt_data)
    {
        Mail::send([],[], function ($message) use($email, $subject, $view_data, $alt_data) {
            $message->to($email)
            ->subject($subject)
            ->setBody($view_data, 'text/html')
            ->addPart($alt_data, 'text/plain');
        });
    }

     /**
     * Makes a unique token from random 32 letter string and base_64 encoding
     *
     * @param [type] $email
     * @return void
     */
    private function makeToken($email)
    {
        // Deleting old token
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
        $this->event(EventType::PASSWORD_RESET_REQUESTED);
        return $token;
    }
    
    private function createUser(array $data)
    {
        $pass = rand(); // generate a random number as password
        $user = User::create([
            'name' => ucfirst($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($pass)
        ]);
        return $user;
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
        return Redirect('login');
    }

    /**
     * Registration password function, used to authenticate token and shows a new password screen
     *
     * @param Request $req
     * @return void
     */
    public function customRegistrationConfirmation(Request $req)
    {
        if (!$req->apitoken) {
            return view('auth.new_password_result', [
                'message' => 'Invalid link. Please try again.'
            ]);
        }
        $token = $req->apitoken;

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

    /**
     * Registration password change confirmation
     *
     * @param Request $req
     * @return void
     */
    public function customRegistrationConfirmed(Request $req)
    {
        $req->validate([
            'password' => 'required|min:6|max:32|confirmed',
            'password_token' => 'required',
            'recaptcha' => ['required', new captchaValid]
        ]);
        $password = $req->password;
        $token = $req->password_token;

        $email = DB::table('password_resets')->where([
            'token' => $token
        ])->first()->email;
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($password);
        $user->email_verified_at = Carbon::now();
        if($user->save()){
            $this->event(EventType::REGISTRATION_CONFIRMED, "", $user->id);
        };

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

    /**
     * Forgot Password Screen Function
     *
     * @param Request $request
     * @return void
     */
    public function forgotPasswordView(Request $request)
    {
        return view('auth.reset_password');
    }

    /**
     * Forgot password post function, send a reset password reset token
     *
     * @param Request $request
     * @return void
     */
    public function forgotPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where(['email' => $request->email])->first();

        if ($user === null) {
            return back()->withErrors([
                'error' => 'User not found!'
            ]);
        }

        $token = $this->makeToken($user->email);

        if($token){
            $this->event(EventType::PASSWORD_RESET_REQUESTED);
        }

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

    /**
     * Sends email to all pending registration/password reset requests
     *
     * @param Request $request
     * @return void
     */
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

                $this->fireGuzzle($rec->email, "Confirm your email address", $view_data, $alt);

                DB::update('update password_resets set last_reminder = "15_mins" where token = ? and email = ?', [$rec->token, $rec->email]);

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

                $this->fireGuzzle($rec->email, "Confirm your email address", $view_data, $alt);
                DB::update('update password_resets set last_reminder = "12_hours" where token = ? and email = ?', [$rec->token, $rec->email]);

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

    /**
     * Saves user settings
     *
     * @param Request $r
     * @return void
     */
    public function saveSettings(Request $r)
    {
        if ($r->settings && is_array($r->settings)) {
            $prefs = json_encode($r->settings);
        } else {
            $prefs = json_encode([]);
        }

        $user = Auth::user();
        $user->preferences = $prefs;
        if($user->save()){
            $this->event(EventType::SETTINGS_SAVED, $prefs);
        }
        return redirect()->action([CustomAuthController::class, 'settings']);
    }

    /**
     * User Settings screen
     *
     * @param Request $r
     * @return void
     */
    public function settings(Request $r)
    {
        // Getting settings
        $settings = json_decode(Auth::user()->preferences);

        return view('user.settings', [
            'settings' => $settings
        ]);
    }

    /**
     * Delete account screen
     *
     * @param Request $request
     * @return void
     */
    public function deleteAccount(Request $request)
    {
        return view('user.delete_account');
    }

    /**
     * Delete account post function. Validates password and confirms account deletion
     *
     * @param Request $request
     * @return void
     */
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
            $this->event(EventType::ACCOUNT_DELETED, "", $id);
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

    /**
     * Event log screen
     *
     * @param Request $req
     * @return void
     */
    public function eventLogView(Request $req)
    {
        $events = Event::where(['user_id'=>Auth::id()])->orderBy('created_at', 'desc')->simplePaginate(25);;
        return view('user.event_log')->with('events', $events);
    }
}
