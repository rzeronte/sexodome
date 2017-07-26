<?php

namespace App\Services\Front;

use App\Model\Scene;

class getSceneIframeService
{
    public function execute($scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return [ 'status' => false, 'message' => 'Scene not found'];
        }

        return [ 'scene' => $scene ];
    }
}