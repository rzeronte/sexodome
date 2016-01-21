<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $table = 'tweets';

    public $timestamps = false;

    public function scene()
    {
        return $this->belongsTo('App\Model\Scene');
    }
}
