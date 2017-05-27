<?php

namespace App\rZeBot;

use App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller;
use Jenssegers\Agent\Agent;
use App\Model\Language;
use App\Model\Site;
use Illuminate\Support\Facades\Route;

class sexodomeKernel extends Controller {

    public $language;
    public $languages;
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
    public $sex_types;

    public function __construct()
    {
        // instanciate global App::make('site')
        $this->instanciateSite();

        // Evitamos cargar si es consola
        if (App::runningInConsole()) {
            return;
        }

        // go to admin panel if no site
        $this->getSiteFromHost();

        // per page setups
        $this->perPage = 48;
        $this->perPageScenes = 10;
        $this->perPageTags = 30;
        $this->perPageCategories = 42;
        $this->perPageJobs = 15;
        $this->perPagePanelPornstars = 12;

        // sex types
        $this->sex_types = [
            'straigth' => 1,
            'gay'      => 2,
            'trans'    => 3
        ];

        // all valid languages
        $this->languages = Cache::remember('languages', env('MEMCACHED_QUERY_TIME', 30), function(){
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

    /**
     * get site or run exceptions from host
     *
     * @return bool
     */
    public function getSiteFromHost()
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];

        $parts = explode(".", $path);

        if (count($parts) == 2 && $_SERVER["HTTP_HOST"] === sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'domain.com'
            return false;
        } elseif (count($parts) == 2 && $_SERVER["HTTP_HOST"] != sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio externo formato 'domain.com'
            $domain = $parts[0];
            $ext = $parts[1];
            $fullDomain = $domain . "." . $ext;

            $site = Cache::remember('site_'.$fullDomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($fullDomain) {
                return Site::where('domain', $fullDomain)->where('status', 1)->first();
            });

            $this->setLanguage($site->language->id); // Seteamos el locale con el idioma del site

            $this->site = $site;

        } elseif (count($parts) == 3 && $_SERVER["HTTP_HOST"] === "accounts.".sexodomeKernel::getMainPlataformDomain()) {
            $this->setLanguage();
            // ----------------------------------- Dominio de miembros formato 'accounts.domain.com'
            return false;
        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] === "www.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'www.domain.com'
            return false;
        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] != "www.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio externo formato 'www.domain.com'
            $domain = $parts[1];
            $ext    = $parts[2];
            $fullDomain = $domain.".".$ext;
            $site = Cache::remember('site_'.$fullDomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($fullDomain) {
                return Site::where('domain', $fullDomain)->where('status', 1)->first();
            });

            if (!$site) {
                abort("403", "Domain not allowed");
                return false;
            } else {
                $this->setLanguage($site->language->id); // Seteamos el locale con el idioma del site
                $this->site = $site;
            }
        } elseif (count($parts) == 3 && $parts[0] !== 'www' && $_SERVER["HTTP_HOST"] != "www.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Subdominio de la plataforma formato 'subdominio.plataforma.com'
            $subdomain = $parts[0];
            $site = Cache::remember('site_'.$subdomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($subdomain) {
                return Site::where('name', $subdomain)->where('status', 1)->first();
            });

            if (!$site) {
                abort("403", "Subdomain not allowed");
                return false;
            } else {
                $this->setLanguage($site->language->id); // Seteamos el locale con el idioma del site
                $this->site = $site;
            }

        } elseif (count($parts) > 3) {
            return false;
        }

        return false;
    }

    /**
     * Instancia la variable global 'site' para utilizar mediante App::make('site')
     */
    public function instanciateSite()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];

            $site = Cache::remember('domain_'.$domain, env('MEMCACHED_QUERY_TIME', 30), function() use ($domain) {
                return Site::where('domain', $domain)->where('status', 1)->first();
            });

            if ($site) {
                App::instance('site', $site);
            } else {
                App::instance('site', false);
            }
        }
    }

    public function setLanguage($language_id = false)
    {
        if ($language_id !== false) {
            $this->language = Cache::remember('language_'.$language_id, env('MEMCACHED_QUERY_TIME', 30), function() use ($language_id) {
                return Language::where('id', '=', $language_id)->first();
            });

            App::setLocale($this->language->code);
        } else {
            $this->language = Cache::remember('language_'.App::getLocale(), env('MEMCACHED_QUERY_TIME', 30), function() {
                return Language::where('code', '=', App::getLocale())->first();
            });
        }
    }

    public function getLanguages()
    {
        return $this->languages;
    }
}