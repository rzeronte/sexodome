<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scene extends Model
{
    use SoftDeletes;

    protected $table = 'scenes';
    protected $dates = ['deleted_at'];

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'scene_tag', 'scene_id', 'tag_id');
    }

    public function pornstars()
    {
        return $this->belongsToMany('App\Model\Pornstar', 'scene_pornstar', 'scene_id', 'pornstar_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Model\Category', 'scene_category', 'scene_id', 'category_id');
    }

    public function translations()
    {
        return $this->hasMany('App\Model\SceneTranslation');
    }

    public function clicks()
    {
        return $this->hasMany('App\Model\SceneClick');
    }

    public function channel()
    {
        return $this->belongsTo('App\Model\Channel');
    }

    public function logspublish()
    {
        return $this->hasMany('App\Model\Logpublish');
    }

    static function getTranslationSearch($query_string = false, $language_id)
    {
        $scenes = Scene::select('scenes.*', 'scene_translations.title', 'scene_translations.permalink')
            ->join('scene_translations', 'scene_translations.scene_id', '=', 'scenes.id')
            ->where('scene_translations.language_id', $language_id);

        if (strlen($query_string) > 0) {
            $scenes->where('scene_translations.title', 'like', '%'.$query_string.'%');
        }

        return $scenes;
    }

    static function getTranslationByPermalink($permalink, $language_id, $site_id = false)
    {
        $scene = Scene::select('scenes.*', 'scene_translations.title', 'scene_translations.permalink')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->where('scene_translations.language_id', $language_id)
            ->where('scene_translations.permalink', 'like', $permalink)
        ;

        if ($site_id !== false) {
            $scene->where('site_id', '=', $site_id);
        }

        return $scene->first();
    }

    static function getTranslationByTitle($title, $language_id)
    {
        return Scene::select('scenes.*', 'scene_translations.title', 'scene_translations.permalink')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->where('scene_translations.language_id', $language_id)
            ->where('scene_translations.title', '=', $title)->first();
    }

    static function getAllTranslated($language_id)
    {
        return Scene::select('scenes.*', 'scene_translations.title', 'scene_translations.permalink')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->where('scene_translations.language_id', $language_id);
    }

    static function getTranslationsForTag($tag_permalink, $language_id)
    {
        return Scene::select('scenes.*', 'scene_translations.title', 'scene_translations.permalink')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->join('scene_tag', 'scenes.id', '=', 'scene_tag.scene_id')
            ->join('tags', 'scene_tag.tag_id', '=', 'tags.id')
            ->join('tag_translations', 'tags.id', '=', 'tag_translations.tag_id')
            ->where('tag_translations.permalink', 'like', $tag_permalink)
            ->where('tag_translations.language_id', $language_id)
            ->where('scene_translations.language_id', $language_id)
            ->orderBy('scenes.id', 'desc');
    }

    static function hasTag($scene_id, $tag_id)
    {
        $tag = Scene::select('scenes.*')
            ->join('scene_tag', 'scene_tag.scene_id', '=', 'scenes.id')
            ->where('scene_tag.scene_id', $scene_id)
            ->where('scene_tag.tag_id', $tag_id)->count();

        if ($tag > 0) {
            return true;
        } else {
            return false;
        }
    }

    static function hasCategory($scene_id, $category_id)
    {
        $category = Scene::select('scenes.*')
            ->join('scene_category', 'scene_category.scene_id', '=', 'scenes.id')
            ->where('scene_category.scene_id', $scene_id)
            ->where('scene_category.category_id', $category_id)->count();

        if ($category > 0) {
            return true;
        } else {
            return false;
        }
    }

    static function getScenesForExporterSearch($query_string, $tag_query_string, $language, $duration, $publish_for, $scene_id, $category_string, $empty_title, $empty_description, $user_id = false) {

        $scenes = Scene::select(
            'scenes.*',
            'scene_translations.title',
            'scene_translations.description',
            'scene_translations.permalink',
            'channels.embed',
            'channels.name AS channel_name',
            'sites.name AS site_name',
            'sites.domain AS site_domain',
            'sites.have_domain AS site_have_domain'
            )
            ->join('sites', 'sites.id', '=', 'scenes.site_id')
            ->join('channels', 'channels.id', '=', 'scenes.channel_id')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->where('scene_translations.language_id', $language)
        ;

        if ($scene_id != "") {
            $scenes->where('scenes.id', $scene_id);
        }

        if ($publish_for !== "notpublished" && $publish_for != false) {
            $scenes->where('scenes.site_id', '=', $publish_for);
        }

        if ($tag_query_string != "") {
            $scenes->join('scene_tag', 'scenes.id', '=', 'scene_tag.scene_id')
                ->join('tags', 'scene_tag.tag_id', '=', 'tags.id')
                ->join('tag_translations', 'tags.id', '=', 'tag_translations.tag_id')
                ->where('tag_translations.language_id', $language)
            ;

            $scenes->where('tag_translations.permalink', 'like', '%'.$tag_query_string.'%');
        }

        if ($query_string != "") {
            $scenes->where('scene_translations.title', 'like', "%".$query_string."%");
        }

        if ($duration != "") {
            $scenes->where('scenes.duration', '>=', $duration);
        }

        if ($category_string != "") {
            $scenes->join('scene_category', 'scenes.id', '=', 'scene_category.scene_id')
                ->join('categories', 'scene_category.category_id', '=', 'categories.id')
                ->join('categories_translations', 'categories.id', '=', 'categories_translations.category_id')
                ->where('categories_translations.language_id', $language)
            ;

            $scenes->where('categories_translations.permalink', 'like', '%'.$category_string.'%');
        }

        if ($empty_title !== false) {
            $scenes->whereNull('scene_translations.title');
        }

        if ($empty_description !== false) {
            $scenes->whereNull('scene_translations.description');
        }

        if ($user_id !== false) {
            $scenes->where('sites.user_id', '=', $user_id);
        }

        return $scenes;
    }

    static function getRemoteSceneIdsFor($database) {
        $sql = "SELECT id FROM scenes WHERE status <> 0 ORDER BY published_at DESC";
        $scenes = DB::connection($database)->select($sql);

        $ids = [];
        foreach($scenes as $scene) {
            $ids[] = $scene->id;
        }

        return $ids;
    }

    static function getRemoteActiveScenesIdsFor($database) {
        $sql = "SELECT id FROM scenes WHERE status =1";
        $scenes = DB::connection($database)->select($sql);

        $ids = [];
        foreach($scenes as $scene) {
            $ids[] = $scene->id;
        }

        return $ids;
    }

    static function getTranslationsForCategory($category_id, $language_id, $order = false)
    {
        $query = Scene::select(
                'scenes.*',
                'scene_translations.title',
                'scene_translations.description',
                'scene_translations.permalink'
            )
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->join('scene_category', 'scenes.id', '=', 'scene_category.scene_id')
            ->join('categories', 'scene_category.category_id', '=', 'categories.id')
            ->join('categories_translations', 'categories.id', '=', 'categories_translations.category_id')
            ->where('categories.id', $category_id)
            ->where('categories_translations.language_id', $language_id)
            ->where('scene_translations.language_id', $language_id)
            ->whereNotNull('scene_translations.permalink')
            ->whereNotNull('scene_translations.title')
        ;

        if ($order !== false) {
            if ($order === "newest") {
                $query->orderBy('scenes.id', 'desc');
            } elseif ($order === "popular") {
                $query->orderBy('scenes.cache_order', 'desc');
            }
        } else {
            $query->orderBy('scenes.id', 'desc');
        }

        return $query;
    }

    static function getTranslationsForPornstar($pornstar_id, $language_id)
    {
        return Scene::select(
            'scenes.*',
            'scene_translations.title',
            'scene_translations.description',
            'scene_translations.permalink'
        )
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->join('scene_pornstar', 'scenes.id', '=', 'scene_pornstar.scene_id')
            ->join('pornstars', 'scene_pornstar.pornstar_id', '=', 'pornstars.id')
            ->where('pornstars.id', $pornstar_id)
            ->where('scene_translations.language_id', $language_id)
            ->whereNotNull('scene_translations.permalink')
            ->whereNotNull('scene_translations.title')
            ->where('status', 1)
            ->orderBy('scenes.id', 'desc')
            ;
    }

    static function addSceneClick($scene, $ua = false)
    {
        // video log
        $sceneClick = new SceneClick();
        $sceneClick->scene_id = $scene->id;
        $sceneClick->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";

        if ($ua !== false) {
            $sceneClick->ua = $ua;
        }

        $sceneClick->save();
    }

    static function getScenesOrderBySceneClicks($site_id)
    {
        $query = Scene::select('scenes.id', DB::raw('count(*) as clicks'))
            ->where('site_id', $site_id)
            ->join('scenes_clicks', 'scenes_clicks.scene_id', '=', 'scenes.id', 'left')
            ->groupBy('scenes.id')
            ->orderBy('clicks')
            ->orderBy('scenes.id', 'desc')
        ;

        return $query;
    }
}