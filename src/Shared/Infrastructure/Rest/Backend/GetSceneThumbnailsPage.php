<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowSceneThumbsCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetSceneThumbnailsPage extends AuthorizedController
{

    public function __invoke($scene_id)
    {
        return view('panel.ajax._ajax_scene_thumbs', (new ShowSceneThumbsCommandHandler())->execute($scene_id));
    }
}
