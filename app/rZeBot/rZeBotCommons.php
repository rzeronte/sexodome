<?php

namespace App\rZeBot;

use App;
use DB;
use Request;
use Response;
use Validator;
use Input;
use Session;
use Config;

use Illuminate\Routing\Controller;

use Jenssegers\Agent\Agent;
use App\Model\Language;
use App\Model\Site;
use Auth;
use Route;

class rZeBotCommons extends Controller {

    public $language;
    public $languages;
    public $locale;
    public $perPage;
    public $videoStatus;
    public $tagsStatus;
    public $agent;
    public $zones;
    public $perPageScenes;
    public $perPageTags;
    public $perPageJobs;
    public $sites;
    public $site;
    public $routeParameters;
    public $cloudFlareCfg;
    public $perPageCategories;

    public function __construct()
    {
        $this->routeParamters = Route::current()->parameters();

        $locale = env('DEFAULT_LOCALE', "en");
        if (isset($this->routeParamters["locale"])) {
            $locale = $this->routeParamters["locale"];
        }

        if (Auth::user()) {
            $this->sites = Site::where('user_id', '=', Auth::user()->id)->get();
        } else {
            $this->sites = false;
        }

        // go to admin panel if no site
        $this->site = rZeBotUtils::getSiteFromHost();
	
        // current language
        if ($this->site) {
            $this->language = Language::where('id', '=', $this->site->language_id)->first();
            $locale = $this->language->code;
        } else {
            $this->language = Language::where('code', '=', $locale)->first();
        }

        $this->perPage = 48;
        $this->perPageScenes = 10;
        $this->perPageTags = 30;
        $this->perPageCategories = 84;
        $this->perPageJobs = 15;

        // set locale
        Request::setLocale($locale);
        $this->locale = $locale;

        // all valid languages
        $this->languages = Language::where('status', 1)->orderBy('code', 'asc')->get();

        // results per page

        // status video config mapping
        $this->videoStatus = array(
            'active'   => 1,
            'inactive' => 0,
        );

        // status tags config mapping
        $this->tagsStatus = array(
            'inactive' => 0,
            'active'   => 1,
        );

        // ads zones codes mapping
        $this->zones = array(
            'home'   => 1,
            'search' => 2,
            'video'  => 3,
        );

        // cloudflarecredentials and setup
        $this->cloudFlareCfg = [
            'ip'    => '91.121.81.154',
            'zone'  => 'sexodome.com',
            'email' => 'assassinsporn@assassinsporn.com',
            'key'   => 'c71115618735b641d5c573fa72f9f6b5372d8'
        ];

        $this->agent = new Agent();
    }

    public static function getMainPlataformDomain()
    {
        return env("MAIN_PLATAFORMA_DOMAIN", "exporter.loc");
    }

    public static function getLogosFolder()
    {
        return env("FOLDER_LOGOS", "../public/logos/");
    }

    public static function getDumpsFolder()
    {
        return env("DEFAULT_DUMPS_FOLDER", "../dumps/");
    }

    public static function getDumpsFolderTmp()
    {
        return rZeBotCommons::getDumpsFolder()."tmp/";
    }

}
