<?php

namespace App\Http\Controllers\Auth;

use App\Model\Scene;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Request;
use Mail;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/';
    protected $loginPath = '/auth/login';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->redirectPath = route('home', ['locale' => Request::getLocale()]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $data['verifyToken'] = md5(microtime().rand(0, 100));

        Mail::send('emails.verify', $data, function ($message) use ($data) {
            $message->from('sexodomeweb@gmail.com', 'Sexodome - Porn Tube Generator');
            $message->subject('Sexodome Account Registration Verify');
            $message->to($data['email']);
        });

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verify_token' => $data['verifyToken']
        ]);
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
