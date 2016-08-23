<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Popunder extends Model
{
    protected $table = 'popunders';

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }
}
