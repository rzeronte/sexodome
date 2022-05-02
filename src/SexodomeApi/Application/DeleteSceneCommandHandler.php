<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Scene;
use Sexodome\Shared\Application\Command\CommandHandler;

class DeleteSceneCommandHandler implements CommandHandler
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
