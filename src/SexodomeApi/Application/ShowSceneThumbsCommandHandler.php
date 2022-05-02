<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Scene;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowSceneThumbsCommandHandler implements CommandHandler
{
    public function execute($scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            return [ 'status' => false, 'message' => 'Scene not found'];
        }

        return [
            'status'  => true,
            'message' => 'showSceneThumbsCommandHandler has been executed',
            'scene'   => $scene,
        ];
    }
}
