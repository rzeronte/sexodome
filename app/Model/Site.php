<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    public $timestamps = false;

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
}
