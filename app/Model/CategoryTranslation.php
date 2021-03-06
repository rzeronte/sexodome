<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $table = 'categories_translations';

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('App\Model\Category');
    }
}
