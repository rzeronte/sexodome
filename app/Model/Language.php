<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Language extends Model
{
    protected $table = 'languages';

    public $timestamps = false;

    static function hasTag($tag_id, $language_id)
    {
        $tag = Language::select('languages.*')
            ->join('language_tag', 'language_tag.language_id', '=', 'languages.id')
            ->join('tags', 'tags.id', '=', 'language_tag.tag_id')
            ->where('language_tag.language_id', $language_id)
            ->where('tags.id', $tag_id)->count();

        if ($tag > 0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAddLanguages($extraLanguage)
    {
        return Language::where('id', 2)->orWhere('id', $extraLanguage)->get();
    }
}
