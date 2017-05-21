<?php

namespace App\rZeBot;

use App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller;
use Jenssegers\Agent\Agent;
use App\Model\Language;
use Illuminate\Support\Facades\Route;

class sexodomeKernel extends Controller {

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
    public $site;
    public $routeParameters;
    public $cloudFlareCfg;
    public $perPageCategories;
    public $redirectWWWToNoWWW301 = false;
    public $perPagePanelPornstars;

    public function __construct()
    {
        if (App::runningInConsole()) {
            return;
        }

        $this->routeParamters = Route::current()->parameters();

        $locale = env('DEFAULT_LOCALE', "en");
        if (isset($this->routeParamters["locale"])) {
            $locale = $this->routeParamters["locale"];
        }
        // go to admin panel if no site
        $this->site = rZeBotUtils::getSiteFromHost();

        $needRedirect301 = rZeBotUtils::checkRedirection301($this->site);
//        if ($needRedirect301 !== false) {
//            $this->redirectWWWToNoWWW301 = $needRedirect301;
//        }

        // Si estámos en un site, usamos configuramos locale del site
        if ($this->site) {
            $language_id = $this->site->language_id;
            $this->language = Cache::remember('language_'.$language_id, env('MEMCACHED_QUERY_TIME', 30), function() use ($language_id) {
                return Language::where('id', '=', $language_id)->first();
            });
            $locale = $this->language->code;
        } else {
            $this->language = Cache::remember('language'.$locale, env('MEMCACHED_QUERY_TIME', 30), function() use ($locale) {
                return Language::where('code', '=', $locale)->first();
            });
        }

        // per page setups
        $this->perPage = 48;
        $this->perPageScenes = 10;
        $this->perPageTags = 30;
        $this->perPageCategories = 42;
        $this->perPageJobs = 15;
        $this->perPagePanelPornstars = 12;

        // set locale
        App::setLocale($locale);
        $this->locale = $locale;

        // all valid languages
        $this->languages = Cache::remember('languages', env('MEMCACHED_QUERY_TIME', 30), function() use ($locale) {
            return Language::where('status', 1)->orderBy('code', 'asc')->get();
        });

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
        return env("MAIN_PLATAFORMA_DOMAIN", "sexodome.loc");
    }

    public static function getLogosFolder()
    {
        return env("FOLDER_LOGOS", "../public/logos/");
    }

    public static function getFaviconsFolder()
    {
        return env("FOLDER_LOGOS", "../public/favicons/");
    }

    public static function getHeadersFolder()
    {
        return env("FOLDER_LOGOS", "../public/headers/");
    }

    public static function getDumpsFolder()
    {
        return env("DEFAULT_DUMPS_FOLDER", "../dumps/");
    }

    public static function getDumpsFolderTmp()
    {
        return sexodomeKernel::getDumpsFolder()."tmp/";
    }

    public static function getThumbnailsFolder()
    {
        return env("DEFAULT_THUMBNAILS_FOLDER", "../dumps/");
    }

    public function getSite()
    {
        return $this->site;
    }
    public function getLanguage()
    {
        return $this->language;
    }
}