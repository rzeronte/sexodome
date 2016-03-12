<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $table = 'words';

    public $timestamps = false;

    public function synonyms()
    {
        return $this->hasMany('App\Model\WordSynonym');
    }
}
