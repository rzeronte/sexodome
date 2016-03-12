<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WordSynonym extends Model
{
    protected $table = 'words_synonym';

    public $timestamps = false;

    public function word()
    {
        return $this->belongsTo('App\Model\Word');

    }
}
