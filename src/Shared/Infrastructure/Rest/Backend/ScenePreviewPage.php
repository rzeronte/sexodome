<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowScenePreviewCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class ScenePreviewPage extends AuthorizedController
{
    public function __invoke($scene_id)
    {
        return view('panel.ajax._ajax_preview', (new ShowScenePreviewCommandHandler())->execute($scene_id));
    }
}
