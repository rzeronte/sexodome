<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zones';

    public $timestamps = false;

    public function ads()
    {
        return $this->hasMany('App\Model\Ad');
    }
}
