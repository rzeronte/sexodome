<?php

namespace App\Services\Front;

use Illuminate\Support\Facades\Cache;
use App\Model\Scene;

class getVideoService
{
    public function execute($permalink, $site_id, $language_id)
    {
        // all valid languages
        $scene = Cache::remember($language_id . "_" . $permalink, env('MEMCACHED_QUERY_TIME', 30), function() use ($permalink, $language_id, $site_id){
            return Scene::getTranslationByPermalink($permalink, $language_id, $site_id);
        });

        if (!$scene) {
            return [ 'status' => false, 'message' => "Scene $site_id not found" ];
        }

        Scene::addSceneClick($scene);

        if ($scene->tags()->count() > 0) {
            $randomTag = $scene->tags()->orderByRaw("RAND()")->first();
            $tag = $randomTag->translations()->where('language_id', $language_id)->first();
            $related = Scene::getTranslationsForTag($tag->name, $language_id, true);
            if (count($related) == 0) {
                $related = Scene::getAllTranslated($language_id);
            }
        } else {
            $related = Scene::getAllTranslated($language_id);
        }

        $related = $related->orderBy('rate', 'desc')->limit(4)->get();
        
        return [
            'status'  => true,
            'video'   => $scene,
            'related' => $related,
        ];
    }
}