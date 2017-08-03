<?php

namespace App\Services\Front;

use App\Model\Scene;

class runOutService
{
    public function execute($scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return [ 'status' => false, 'message' => "Scene $scene_id not found"];
        }

        Scene::addSceneClick($scene, $ua = false);

        return [ 'status' => true, 'url' => $scene->url];
    }
}