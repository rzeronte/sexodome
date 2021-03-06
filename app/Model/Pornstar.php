<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pornstar extends Model
{
    protected $table = 'pornstars';

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }

    public function scenes()
    {
        return $this->belongsToMany('App\Model\Scene', 'scene_pornstar', 'pornstar_id', 'scene_id');
    }

    public static function getPornstarByPermalink($permalink, $site_id)
    {
        return Pornstar::where('pornstars.site_id', '=', $site_id)
            ->where('permalink', $permalink)
            ->first()
        ;
    }

}