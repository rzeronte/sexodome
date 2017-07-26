<?php

namespace App\Services\Front;

use App\Model\Scene;

class runOutService
{
    public function execute(integer $scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return [ 'status' => false, 'message' => 'Scene not found'];
        }

        Scene::addSceneClick($scene, $ua = false);

        return $scene->url;
    }
}