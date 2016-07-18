<?php

namespace App\Model;

use App\rZeBot\rZeBotCommons;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'site_tag', 'site_id', 'tag_id');
    }


    public function categories()
    {
        return $this->belongsToMany('App\Model\Category', 'site_category', 'site_id', 'category_id');
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

    public function getTotalScenes()
    {
        $scenes = Scene::where('site_id', $this->id)
            ->where('status',1)
            ->count()
        ;

        return $scenes;
    }
}