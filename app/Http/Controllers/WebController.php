<?php

namespace App\Http\Controllers;

use App;
use DB;
use Request;
use Validator;
use Input;
use Session;
use URL;
use Auth;

use App\rZeBot\rZeBotCommons;

class WebController extends Controller
{
    var $commons;
    public function __construct()
    {
        $this->commons = new rZeBotCommons();
    }

    public function home()
    {
        return view('web.home', [
        ]);
    }
}