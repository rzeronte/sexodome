<?php

namespace App\Model;

use App\rZeBot\sexodomeKernel;
use Illuminate\Database\Eloquent\Model;
use DB;
use App;

class Site extends Model
{
    protected $table = 'sites';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function type()
    {
        return $this->belongsTo('App\Model\Type');
    }

    public function language()
    {
        return $this->belongsTo('App\Model\Language');
    }

    public function tags()
    {
        return $this->hasMany('App\Model\Tag');
    }

    public function popunders()
    {
        return $this->hasMany('App\Model\Popunder');
    }

    public function scenes()
    {
        return $this->hasMany('App\Model\Scene');
    }

    public function categories()
    {
        return $this->hasMany('App\Model\Category');
    }

    public function pornstars()
    {
        return $this->hasMany('App\Model\Pornstar');
    }

    public function infojobs()
    {
        return $this->hasMany('App\Model\InfoJobs');
    }

    public function cronjobs()
    {
        return $this->hasMany('App\Model\CronJob');
    }

    public function getAnalytics($fi, $ff)
    {
        $analytics = Analytics::where('site_id', $this->id)
            ->where("date", ">=", $fi)
            ->where("date", "<=", $ff)
        ;

        return $analytics;
    }

    static function hasCategory($category_id, $site_id)
    {
        $category = Site::select('sites.*')
            ->join('site_category', 'site_category.site_id', '=', 'sites.id')
            ->join('categories', 'categories.id', '=', 'site_category.category_id')
            ->where('site_category.site_id', $site_id)
            ->where('categories.id', $category_id)->count()
        ;

        if ($category > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getHost()
    {
        if ($this->have_domain == 0) {
            return $this->name.".".sexodomeKernel::getMainPlataformDomain();
        } else {
            return  $this->domain;
        }
    }

    public function getTotalScenes($feed_id = false)
    {
        $scenes = Scene::where('site_id', $this->id)->where('status', 1);

        if ($feed_id !== false) {
            $scenes->where('feed_id', $feed_id);
        }

        return $scenes->count();
    }

    public function getSitemap()
    {
        return $this->getHost() .".xml";
    }

    public function getCSSThemeFilename($ignore_if_exists = false)
    {
        $file = 'tubeThemes/'.str_slug($this->getHost()).".css";

        if (!file_exists($file) && $ignore_if_exists == false) {
            return "theme.css";
        } else {
            return str_slug($this->getHost()).".css";
        }
    }

    public function getClicks($fi, $ff)
    {
        $query = SceneClick::select(DB::raw("count(*) as total, DATE(scenes_clicks.created_at) as dia"))
            ->join('scenes', 'scenes.id', '=', 'scenes_clicks.scene_id')
            ->where('scenes.site_id', '=', $this->id)
            ->groupBy('dia')
        ;

        return $query;
    }

    public function getUAs($fi, $ff)
    {
        $query = SceneClick::select(DB::raw("count(*) as y, ua as name"))
            ->join('scenes', 'scenes.id', '=', 'scenes_clicks.scene_id')
            ->where('scenes.site_id', '=', $this->id)
            ->groupBy('ua')
        ;

        return $query;
    }

    public function getH2Home()
    {
        return $this->h2_home;
    }

    public function getH2Category($translation)
    {
        $h2_category = str_replace("{category}", $translation, $this->h2_category);

        return ucwords($h2_category);
    }

    public function getH2Pornstars()
    {
        return $this->h2_pornstars;
    }

    public function getH2Pornstar($translation)
    {
        $h2_pornstar = str_replace("{pornstar}", $translation, $this->h2_pornstar);

        return $h2_pornstar;
    }

    public function getCategoriesTitle($page = false)
    {
        $seo_title = str_replace("{domain}", $this->getHost(), $this->title_index);

        if ($page > 1 && $page != false) {
            $seo_title.= " - " . ucwords(trans('tube.page')) . " " . $page;
        }

        return $seo_title;
    }

    public function getCategoriesDescription()
    {
        return str_replace("{domain}", $this->getHost(), $this->description_index);
    }

    public function getCategoryTitle($translation, $page = false)
    {
        // seo
        $seo_title = str_replace("{category}", $translation, $this->title_category);
        $seo_title = str_replace("{domain}", $this->getHost(), $seo_title);

        if ($page > 1 && $page !== false) {
            $seo_title.= " - " . ucwords(trans('tube.page')) . " " . $page;
        }

        return $seo_title;
    }

    public function getCategoryDescription($translation)
    {
        $seo_description = str_replace("{category}", $translation, $this->description_category);
        $seo_description = str_replace("{domain}", $this->getHost(), $seo_description);

        return $seo_description;
    }

    public function getPornstarsTitle($page = false)
    {
        // seo
        $seo_title = str_replace("{domain}", $this->getHost(), $this->title_pornstars);

        if ($page > 1 && $page !== false) {
            $seo_title.= " - " . ucwords(trans('tube.page')) . " " . $page;
        }

        return $seo_title;
    }

    public function getPornstarsDescription()
    {
        return str_replace("{domain}", $this->getHost(), $this->description_pornstars);

    }

    public function getPornstarTitle($translation)
    {
        $seo_title = str_replace("{pornstar}", $translation, $this->title_pornstar);
        $seo_title = str_replace("{domain}", $this->getHost(), $seo_title);

        return $seo_title;
    }

    public function getPornstarDescription($translation)
    {
        $seo_description = str_replace("{pornstar}", $translation, $this->description_pornstar);
        $seo_description = str_replace("{domain}", $this->getHost(), $seo_description);

        return $seo_description;
    }

    public function getSceneTitle($scene)
    {
        $seo_title = str_replace("{domain}", $this->getHost(), $scene->title);

        return $seo_title;

    }

    public function getSceneDescription($scene)
    {
        $seo_description = str_replace("{domain}", $this->getHost(), $scene->description);

        // Si no hay descripción hacemos un montaje: title + categorías + host
        if (strlen(trim($seo_description)) == 0) {
            $array_categories = [];
            foreach ($scene->categories()->get() as $category) {
                $translation = $category->translations()->where('language_id', $this->language_id)->first();
                $array_categories[] = $translation->name;
            }

            $seo_description = $scene->title . " " . implode("-", $array_categories) . " " . $this->getHost();
        }

        return $seo_description;

    }
}