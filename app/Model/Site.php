<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    public $timestamps = false;
    public $num_tiers;

    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'site_tagtiers', 'site_id', 'tag_id');
    }

    public function getAnalytics($fi, $ff)
    {
        $analytics = Analytics::where('site_id', $this->id)
            ->where("date", ">=", $fi)
            ->where("date", "<=", $ff)
        ;

        return $analytics;
    }

    static function getNumTiers()
    {
        return 3;
    }

    static function hasTag($site_id, $tag_id, $tipo)
    {
        $tag = Site::select('sites.*')
            ->join('site_tagtiers', 'site_tagtiers.site_id', '=', 'sites.id')
            ->where('site_tagtiers.tipo', $tipo)
            ->where('site_tagtiers.site_id', $site_id)
            ->where('site_tagtiers.tag_id', $tag_id)->count();

        if ($tag > 0) {
            return true;
        } else {
            return false;
        }
    }



}
