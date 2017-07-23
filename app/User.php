<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Site;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verify_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isVerify()
    {
        if ($this->verify == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function setVerifyToken($token)
    {
        $this->verify_token = $token;
    }

    public function getSites()
    {
        return Site::where('user_id', '=', Auth::user()->id)
            ->orderBy('language_id', 'asc')
            ->get()
        ;
    }
}
