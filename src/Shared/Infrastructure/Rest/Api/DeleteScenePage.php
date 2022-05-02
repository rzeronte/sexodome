<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\DeleteSceneCommandHandler;
use Illuminate\Http\JsonResponse;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class DeleteScenePage extends AuthorizedController
{
    public function __invoke($scene_id): JsonResponse
    {
        return new JsonResponse((new DeleteSceneCommandHandler())->execute($scene_id));
    }
}
