<?php

namespace Sexodome\Shared\Infrastructure\Rest;

use App\Http\Controllers\Controller;

class AuthorizedController extends Controller
{
    public function __construct()
    {
        $this->middleware('CheckVerifyUser');
        $this->middleware('auth');
    }
}
