<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\CategoryUnlockCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CategoryUnlockPage extends AuthorizedController
{
    public function __invoke($category_translation_id): JsonResponse
    {
        return new JsonResponse((new CategoryUnlockCommandHandler())->execute($category_translation_id));
    }
}
