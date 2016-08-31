<?php

namespace App\Model;

use App\rZeBot\rZeBotCommons;
use Illuminate\Database\Eloquent\Model;
use DB;

class Site extends Model
{
    protected $table = 'sites';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function language()
    {
        return $this->belongsTo('App\Model\Language');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'site_tag', 'site_id', 'tag_id');
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
            return $this->name.".".rZeBotCommons::getMainPlataformDomain();
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

    public function getClicks($fi, $ff)
    {
        $query = SceneClick::select(DB::raw("count(*) as total, DATE(scenes_clicks.created_at) as dia"))
            ->join('scenes', 'scenes.id', '=', 'scenes_clicks.scene_id')
            ->where('scenes.site_id', '=', $this->id)
            ->groupBy('dia')
        ;

        return $query;
    }
}