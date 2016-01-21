<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    protected $table = 'tag_translations';

    public $timestamps = false;

    public function tag()
    {
        return $this->belongsTo('App\Model\Tag');
    }
}
