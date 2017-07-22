<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Language extends Model
{
    protected $table = 'languages';

    public $timestamps = false;

    static function getAddLanguages($extraLanguage)
    {
        return Language::where('id', 2)->orWhere('id', $extraLanguage)->get();
    }
}
