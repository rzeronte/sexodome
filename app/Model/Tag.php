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

    public function translations()
    {
        return $this->hasMany('App\Model\TagTranslation');
    }

    public function categories()
    {
        return $this->hasMany('App\Model\CategoryTag');
    }

    public function countScenesLang($language_id)
    {
        return Scene::select('scenes.id')
            ->join('scene_tag', 'scene_tag.scene_id', '=', 'scenes.id')
            ->join('tags', 'tags.id', '=', 'scene_tag.tag_id')
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'scene_tag.tag_id')
            ->where('tag_translations.language_id', '=', $language_id)
            ->where('tags.id', '=', $this->id)
            ->count();
    }

    static function getTranslationSearch($query_string = false, $language_id = false, $site_id = false)
    {
        $tags = Tag::select(
            'tags.*',
            'tag_translations.name',
            'tag_translations.permalink',
            'tag_translations.id as translationId'
            )
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'tags.id')
        ;

        if ($language_id !== false) {
            $tags->where('tag_translations.language_id', '=', $language_id);
        }

        if ($site_id !== false) {
            $tags->where('tags.site_id', '=', $site_id);
        }

        if ($query_string !== false) {
            $tags->where('tag_translations.name', 'like', '%'.$query_string.'%');
        }

        return $tags;
    }

    static function getTranslationByScene($scene, $language_id)
    {
        $tags = $scene->tags()->select(
            'tags.*',
            'tag_translations.name',
            'tag_translations.permalink',
            'tag_translations.id as translationId'
        )
        ->join('tag_translations', 'tag_translations.tag_id', '=', 'tags.id')
        ->where('tag_translations.language_id', $language_id)
        ;

        return $tags;
    }

    static function getTranslationByCategory($category, $language_id)
    {
        $tags = $category->tags()->select(
                'tags.*',
                'tag_translations.name',
                'tag_translations.permalink',
                'tag_translations.id as translationId'
            )
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'tags.id')
            ->where('tag_translations.language_id', $language_id)
        ;

        return $tags;
    }

    static function getTranslationByName($query_string = false, $language_id)
    {
        $tags = Tag::select('tags.*', 'tag_translations.name', 'tag_translations.permalink', 'tag_translations.id as translationId')
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'tags.id')
            ->where('tag_translations.language_id', $language_id);

        if ($query_string != false) {
            $tags->where('tag_translations.name', 'like', $query_string);
        }

        return $tags;
    }

    static function getTranslationSearchFromArray($tags_string = false, $language_id)
    {
        $tags = Tag::select('tags.*', 'tag_translations.name', 'tag_translations.permalink', 'tag_translations.id as translationId')
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'tags.id')
            ->where('tag_translations.language_id', $language_id);

        if ($tags_string != false) {
            $tags->where(function($query){
                return $query;
                    foreach($tags_string as $tag) {
                        $query->orWhere('tag_translations.name', '=', $tag);
                    }
            });
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