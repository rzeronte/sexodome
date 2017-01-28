<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FixTranslation extends Model
{
    protected $table = 'fixtranslations';

    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo('App\Model\Language');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
