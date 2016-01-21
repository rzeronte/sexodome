<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $table = 'ads';

    public function zone()
    {
        return $this->belongsTo('App\Model\Zone');
    }
}
