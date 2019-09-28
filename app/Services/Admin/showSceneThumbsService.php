<?php

namespace App\Services\Admin;

use App\Model\Scene;

class showSceneThumbsService
{
    public function execute($scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return [ 'status' => false, 'message' => 'Scene not found'];
        }

        return [
            'status'  => true,
            'message' => 'showSceneThumbsService has been executed',
            'scene'   => $scene,
        ];
    }
}