<?php

namespace DDD\Application\Service\Admin;

class deleteSceneService
{
    public function execute($scene_id)
    {
        try {
            $scene = Scene::findOrFail($scene_id);
            $scene->delete();
            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}