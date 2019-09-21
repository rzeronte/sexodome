<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seo';

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }
}
