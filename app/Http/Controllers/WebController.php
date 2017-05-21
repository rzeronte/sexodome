<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\rZeBot\sexodomeKernel;

class WebController extends Controller
{
    var $commons;
    public function __construct()
    {
        $this->commons = new sexodomeKernel();
    }

    public function home()
    {
        return view('web.home', [
        ]);
    }

    public function GooglePosition()
    {
        $keyword = Input::get('keyword', false);
        $url     = Input::get('url', false);

        if (Request::isMethod('get') && $keyword !== false && $url !== false) {

            $position = GoogleScrapper::scrape($keyword, array($url));

            return view('web.google_keyword_position', [
                'position' => $position,
                'keyword'  => $keyword,
                'url'      => $url
            ]);
        }
        return view('web.google_keyword_position', [
        ]);
    }

    public function webping()
    {
        return "ping";
    }}
