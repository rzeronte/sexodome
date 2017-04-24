<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/auth/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Verify user througth token
     * @return \Illuminate\Http\Response
     */
    public function verify($token) {

        $user = User::where('verify_token', $token)->first();

        if ($user) {
            $blade = "panel.welcome";
            $user->verify = 1;
            $user->save();

            $mailData = [
                'email' => $user->email
            ];

            Mail::send('emails.welcome', $mailData, function ($message) use ($user){
                $message->from('sexodomeweb@gmail.com', 'Sexodome - Porn Tube Generator');
                $message->subject('Sexodome Account Registration Successful');
                $message->to($user->email);
            });

        } else {
            $blade = "panel.errors.verifyfailed";
        }

        return response()->view($blade, []);
    }
}
