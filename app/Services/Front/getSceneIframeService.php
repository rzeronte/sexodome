<?php

namespace App\Services\Front;

use App\Model\Scene;

class getSceneIframeService
{
    public function execute($scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return [ 'status' => false, 'message' => "Scene $scene_id not found"];
        }

        return [ 'status' => true, 'scene' => $scene ];
    }
}