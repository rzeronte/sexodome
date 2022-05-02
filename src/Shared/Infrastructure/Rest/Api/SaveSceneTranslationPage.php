<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\UpdateSceneTranslationCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class SaveSceneTranslationPage extends AuthorizedController
{
    public function __invoke($scene_id): JsonResponse
    {
        return new JsonResponse((new UpdateSceneTranslationCommandHandler())->execute(
            $scene_id,
            Request::input('title'),
            Request::input('description'),
            Request::input('selectedThumb', null)
        ));
    }
}
