<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    public function translations()
    {
        return $this->hasMany('App\Model\CategoryTranslation');
    }

}
