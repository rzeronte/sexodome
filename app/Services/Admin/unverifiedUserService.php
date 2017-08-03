<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;

class unverifiedUserService
{
    public function execute()
    {
        Auth::logout();

        return [ 'status' => true ];
    }
}