<?php

namespace DDD\Application\Service\Admin;

class showScenePreviewService
{
    public function execute($scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return false;
        }

        return [
            'scene' => $scene
        ];
    }
}