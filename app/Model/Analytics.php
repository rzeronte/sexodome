<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    protected $table = 'analytics';

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }

}
