<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Scene extends Model
{
    protected $table = 'scenes';

    /**
     * Get the tags for the scene.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'scene_tag', 'scene_id', 'tag_id');
    }

    public function translations()
    {
        return $this->hasMany('App\Model\SceneTranslation');
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

    static function getTranslationByPermalink($permalink, $language_id)
    {
        return Scene::select('scenes.*', 'scene_translations.title', 'scene_translations.permalink')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->where('scene_translations.language_id', $language_id)
            ->where('scene_translations.permalink', 'like', $permalink)->first();
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

    static function getScenesForExporterSearch($query_string, $tag_query_string, $remote_scenes, $language) {
        $scenes = Scene::select('scenes.*', 'scene_translations.title', 'scene_translations.description', 'scene_translations.permalink')
            ->join('scene_translations', 'scenes.id', '=', 'scene_translations.scene_id')
            ->where('scene_translations.language_id', $language)
            ->orderBy('scenes.id')
        ;

        if ($tag_query_string != "") {
            $scenes->join('scene_tag', 'scenes.id', '=', 'scene_tag.scene_id')
                ->join('tags', 'scene_tag.tag_id', '=', 'tags.id')
                ->join('tag_translations', 'tags.id', '=', 'tag_translations.tag_id')
                ->where('tag_translations.language_id', $language)
            ;

            $scenes->where('tag_translations.permalink', 'like', $tag_query_string);

        }

        if ($query_string != "") {
            $scenes->where('scene_translations.title', 'like', $query_string);
        }

        if (count($remote_scenes)) {
            $scenes->whereIn('scenes.id', $remote_scenes);
        }

        return $scenes;
    }

    static function getRemoteSceneIdsFor($database) {
        $sql = "SELECT id FROM scenes WHERE status <> 0";
        $scenes = DB::connection($database)->select($sql);

        $ids = [];
        foreach($scenes as $scene) {
            $ids[] = $scene->id;
        }

        return $ids;
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
}
