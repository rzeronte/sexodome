<?php

namespace App\Services\Admin;

use App\Model\Scene;

class showScenePreviewService
{
    public function execute($scene_id)
    {
        try {
            $scene = Scene::find($scene_id);

            if (!$scene) {
                return false;
            }

            return ['status' => true, 'scene' => $scene];

        } catch(\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}