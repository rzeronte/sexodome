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
        $this->instanciateSite();

        if (App::runningInConsole()) {
            return;
        }

        $this->setSiteAndLanguageOrFail();

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

        // cloudflarecredentials and setup
        $this->cloudFlareCfg = [
            'ip'    => '91.121.81.154',
            'zone'  => 'sexodome.com',
            'email' => 'assassinsporn@assassinsporn.com',
            'key'   => 'c71115618735b641d5c573fa72f9f6b5372d8'
        ];

        $this->agent = new Agent();
    }

    /**
     * comprueba si el acceso es al frontal de 'sexodome.com'
     *
     * @return bool
     */
    public function isSexodomeFront()
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];

        $parts = explode(".", $path);

        if (count($parts) == 2 && $_SERVER["HTTP_HOST"] === sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'sexodome.com'
            return true;
        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] === "www.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'www.domain.com'
            return true;
        }

        return false;
    }

    /**
     * comprueba si el acceso es al backend 'accounts.sexodome.com'
     *
     * @return bool
     */
    public function isSexodomeBackend()
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];

        $parts = explode(".", $path);

        if (count($parts) == 3 && $_SERVER["HTTP_HOST"] === "accounts.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de miembros formato 'accounts.domain.com'
            return true;
        }

        return false;
    }

    /**
     * comprueba si el acceso es a un dominio dado de alta en sexodome
     *
     * @return bool
     */
    public function isSexodomeDomain()
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];
        $parts = explode(".", $path);

        if (count($parts) == 2 && $_SERVER["HTTP_HOST"] != sexodomeKernel::getMainPlataformDomain()) {
            return true;
        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] != "www.".sexodomeKernel::getMainPlataformDomain()) {
            return true;
        }

        return false;
    }

    /**
     * comprueba si el acceso es a un subdominio dado de alta en sexodome
     *
     * @return bool
     */
    public function isSexodomeSubDomain()
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];
        $parts = explode(".", $path);

        if (count($parts) == 3 && $parts[0] !== 'www' && $_SERVER["HTTP_HOST"] != "www.".sexodomeKernel::getMainPlataformDomain()) {
            return true;
        }

        return false;
    }

    /**
     * Obtiene el sitio en función del dominio o tira 403 si el dominio está inactivo
     */
    public function setSiteFromDomainOrFail()
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];
        $parts = explode(".", $path);

        $domain = $parts[0];
        $ext = $parts[1];
        $fullDomain = $domain . "." . $ext;

        $site = Cache::remember('site_'.$fullDomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($fullDomain) {
            return Site::where('domain', $fullDomain)->where('status', 1)->first();
        });

        if (!$site) {
            abort(403, 'Site not available');
        }

        $this->site = $site;
    }

    /**
     * Obtiene el sitio en función del sub-dominio o tira 403 si el dominio está inactivo
     */
    public function setSiteFromSubDomainOrFail()
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];
        $parts = explode(".", $path);

        // ----------------------------------- Subdominio de la plataforma formato 'subdominio.plataforma.com'
        $subdomain = $parts[0];

        $site = Cache::remember('site_'.$subdomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($subdomain) {
            return Site::where('name', $subdomain)->where('status', 1)->first();
        });

        if (!$site) {
            abort("403", "Site is not available");
            return false;
        } else {
            $this->setLanguage($site->language->id); // Seteamos el locale con el idioma del site
            $this->site = $site;
        }
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

    /**
     * Establece el idioma de sexodome, si no especificamos, utiliza el del locale q exista
     *
     * @param bool $language_id
     */
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

    /**
     * Establece sitio y language en función de si estámos en frontend o backend, con sitio activo o sin él.
     */
    public function setSiteAndLanguageOrFail()
    {
        if ($this->isSexodomeBackend()) {

            $this->setLanguage();

        } elseif ($this->isSexodomeDomain()) {

            $this->setSiteFromDomainOrFail();
            $this->setLanguage($this->site->language->id);

        } elseif ($this->isSexodomeSubDomain()) {

            $this->setSiteFromSubDomainOrFail();
            $this->setLanguage($this->site->language->id);

        }
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

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getUA() {
        return $this->agent;
    }

}