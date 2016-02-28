<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SiteTagTier extends Model
{
    protected $table = 'site_tagtiers';
    public $timestamps = false;

//    public function site()
//    {
//        return $this->belongsTo('App\Model\Site');
//    }
//
//    public function tag()
//    {
//        return $this->belongsTo('App\Model\Tag');
//    }
}
