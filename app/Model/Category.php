<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\rZeBot\rZeBotUtils;

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

    public function translation($language_id)
    {
        return $this->translations()->where('language_id', $language_id)->first();
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
            ->where('categories_translations.language_id', $language_id)
        ;

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

    static function getForTranslation($status = false, $site_id, $language_id)
    {
        $categories = Category::select(
            'categories.*',
            'categories_translations.name',
            'categories_translations.permalink',
            'categories_translations.thumb',
            'categories_translations.thumb_locked'
            )
            ->join('categories_translations', 'categories_translations.category_id', '=', 'categories.id')
            ->where('categories_translations.language_id', $language_id)
            ->where('categories.site_id', $site_id)
            ->orderBy('categories.cache_order', 'DESC')
            ->orderBy('categories.nscenes', 'DESC')
        ;

        if ($status !== false) {
            $categories->where('categories.status',$status);
        }

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
            ->whereIn('category_tags.tag_id', $arrayTagIds)
        ;
    }

    static function getTranslationFromPermalink($permalink, $site_id, $language_id)
    {
        return CategoryTranslation::join('categories','categories.id', '=', 'categories_translations.category_id')
            ->where('categories_translations.permalink', $permalink)
            ->where('categories.site_id', $site_id)
            ->where('categories_translations.language_id', $language_id)
            ->first()
        ;
    }

    static function getTranslationById($category_id, $site_id, $language_id)
    {
        return Category::select(
                'categories.id',
                'categories.site_id',
                'categories.status',
                'categories_translations.permalink',
                'categories_translations.thumb',
                'categories_translations.thumb_locked'
            )
            ->join('categories_translations','categories.id', '=', 'categories_translations.category_id')
            ->where('categories.id', $category_id)
            ->where('categories.site_id', $site_id)
            ->where('categories_translations.language_id', $language_id)
            ->first()
        ;
    }

    /**
     * update thumbnail for a category
     *
     * @param $category
     * @param null $exclude_scene_ids
     * @return bool
     */
    public static function updateCategoryThumbnail($category, $exclude_scene_ids = null, $ignore_locked = false)
    {
        $sceneRNDquery = $category->scenes()
            ->select('scenes.id', 'scenes.preview')
            ->orderBy('scenes.cache_order', 'desc');
        ;

        if ($exclude_scene_ids !== null) {
            $sceneRNDquery->whereNotIn('scenes.id', $exclude_scene_ids);
        }

        $sceneRND = $sceneRNDquery->first();

        if ($sceneRND) {
            $img = $sceneRND->preview;

            // la thumb es dependiente al idioma, seteamos todos con esta thumbnail
            foreach($category->translations()->where('language_id', $category->site->language_id)->get() as $translation) {

                if ($translation->thumb_locked == 1 && $ignore_locked == false) {
                    rZeBotUtils::message("[updateCategoryThumbnail] Thumbnail locked | site_id: $category->site_id | $category->text($category->id), tiene " . $category->scenes()->count() . " escenas | Excluyendo: ". count($exclude_scene_ids), "info", 'kernel');
                    continue;
                }

                rZeBotUtils::message("[updateCategoryThumbnail] Updating thumbnail  | site_id: $category->site_id) | $category->text($category->id), tiene " . $category->scenes()->count() . " escenas | Excluyendo: ". count($exclude_scene_ids), "info", 'kernel');
                $translation->thumb = $img;
                $translation->save();
            }

            return $sceneRND->id;
        } else {
            rZeBotUtils::message("[updateCategoryThumbnail] Thumbnail | site_id: $category->site_id) | $category->text($category->id), tiene " . $category->scenes()->count() . " escenas", "error", 'kernel');

            return false;
        }
    }
}