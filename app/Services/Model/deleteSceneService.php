<?php

namespace App\Services\Model;

use App\Model\Scene;

class deleteSceneService
{
    public function execute($scene_id)
    {
        try {
            $scene = Scene::find($scene_id);

            if (!$scene) {
                return [ 'status' => false, 'message' => "Scene $scene_id not found" ];
            }

            $scene->delete();

            return ['status' => true ];
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage() ];
        }
    }
}