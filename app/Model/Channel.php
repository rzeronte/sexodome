<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';

    public $timestamps = false;

    public function scenes()
    {
        return $this->hasMany('App\Model\Scene');
    }
}
