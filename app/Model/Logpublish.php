<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Logpublish extends Model
{
    protected $table = 'logpublish';

    public $timestamps = false;

    public function scenes()
    {
        return $this->hasMany('App\Model\Scene');
    }
}
