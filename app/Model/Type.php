<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';

    public function sites()
    {
        return $this->hasMany('App\Model\Sites');
    }

}