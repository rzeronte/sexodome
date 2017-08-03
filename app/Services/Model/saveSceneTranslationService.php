<?php

namespace App\Services\Model;

use App\Model\SceneTranslation;
use App\Model\Scene;

class saveSceneTranslationService
{
    public function execute($scene_id, $title, $description, $thumb)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return json_encode(['status' => false, 'message' => 'Site not found']);
        }

        $site = $scene->site;

        $sceneTranslation = SceneTranslation::where('scene_id', $scene_id)
            ->where('language_id', $site->language->id)
            ->first()
        ;

        $scene->thumb_index = $thumb;
        $scene->save();

        if ($sceneTranslation) {

            $sceneTranslation->title = $title;
            $sceneTranslation->permalink = str_slug($title);
            $sceneTranslation->description = $description;
            $sceneTranslation->save();

            return [
                'title'       => $sceneTranslation->title,
                'description' => $sceneTranslation->description,
                'scene_id'    => $scene_id,
                'status'      => true
            ];
        } else {
            return ['status' => false, 'message' => 'SceneTranslation not found'];
        }
    }
}