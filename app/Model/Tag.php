<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    public function scenes()
    {
        return $this->belongsToMany('App\Model\Scene', 'scene_tag', 'tag_id', 'scene_id');
    }

    public function domains()
    {
        return $this->belongsToMany('App\Model\Domain', 'domain_tag', 'domain_id', 'tag_id');
    }

    public function translations()
    {
        return $this->hasMany('App\Model\TagTranslation');
    }

    static function getTranslationSearch($query_string = false, $language_id)
    {
        $tags = Tag::select('tags.*', 'tag_translations.name', 'tag_translations.permalink', 'tag_translations.id as translationId')
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'tags.id')
            ->where('tag_translations.language_id', $language_id);

        if ($query_string != false) {
            $tags->where('tag_translations.name', 'like', '%'.$query_string.'%');
        }

        return $tags;
    }

    static function getTranslationByStatus($status, $language_id)
    {
        $tags = Tag::select('tags.*', 'tag_translations.name', 'tag_translations.permalink')
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'tags.id')
            ->where('tag_translations.language_id', $language_id)
            ->where('tags.status',$status)
            ->orderBy('tag_translations.name', 'asc');

        return $tags;
    }

}
