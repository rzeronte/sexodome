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

    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'category_tags', 'category_id', 'tag_id');
    }

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }

    public function scenes()
    {
        return $this->belongsToMany('App\Model\Scene', 'scene_category', 'category_id', 'scene_id');
    }

    static function getTranslationSearch($query_string = false, $language_id = false, $site_id = false, $order_by_nscenes = false)
    {
        $categories = Category::select(
                'categories.*',
                'categories_translations.name',
                'categories_translations.permalink',
                'categories_translations.id as translationId'
            )
            ->join('categories_translations', 'categories_translations.category_id', '=', 'categories.id')
            ->where('categories_translations.language_id', $language_id);

        if ($query_string !== false) {
            $categories->where('categories_translations.name', 'like', '%'.$query_string.'%');
        }

        if ($site_id !== false) {
            $categories->where('categories.site_id', $site_id);
        }

        if ($order_by_nscenes !== false) {
//            $categories->orderBy('categories.nscenes', 'DESC');
            $categories->orderByRaw("categories.nscenes DESC, categories.nscenes ASC");
        }

        return $categories;
    }

    static function getTranslationByName($query_string = false, $language_id, $site_id = false)
    {
        $categories = Category::select('categories.*', 'categories_translations.name', 'categories_translations.permalink', 'categories_translations.id as translationId')
            ->join('categories_translations', 'categories_translations.category_id', '=', 'categories.id')
            ->where('categories_translations.language_id', $language_id);

        if ($query_string != false) {
            $categories->where('categories_translations.name', '=', $query_string);
        }

        if ($site_id !== false) {
            $categories->where('categories.site_id', $site_id);
        }

        return $categories;
    }

    static function getTranslationByStatus($status, $language_id)
    {
        $categories = Category::select(
            'categories.*',
            'categories_translations.name',
            'categories_translations.permalink',
            'categories_translations.thumb'
            )
            ->join('categories_translations', 'categories_translations.category_id', '=', 'categories.id')
            ->where('categories_translations.language_id', $language_id)
            ->where('categories.status',$status);

        return $categories;
    }

    public function countScenesLangAndSite($language_id, $site_id)
    {
        return Scene::select('scenes.id')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->join('scene_category', 'scene_category.scene_id', '=', 'scenes.id')
            ->join('categories', 'categories.id', '=', 'scene_category.category_id')
            ->join('categories_translations', 'categories_translations.category_id', '=', 'scene_category.category_id')
            ->where('categories_translations.language_id', '=', $language_id)
            ->where('categories.id', '=', $this->id)
            ->where('scenes.site_id', '=', $site_id)
            ->where('scene_translations.language_id', '=', $language_id)
            ->whereNotNull('categories_translations.permalink')
            ->whereNotNull('scene_translations.permalink')
            ->whereNotNull('scene_translations.title')
            ->count()
        ;
    }

    static function getCategoriesFromTagsArray($site_id, $arrayTagIds)
    {
        return Category::select('categories.id')
            ->where('site_id', $site_id)
            ->join('category_tags', 'category_tags.category_id', '=', 'categories.id')
            ->whereIn('category_tags.tag_id', $arrayTagIds);
        ;
    }
}