<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Scene;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowScenePreviewCommandHandler implements CommandHandler
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
