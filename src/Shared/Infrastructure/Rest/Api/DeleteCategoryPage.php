<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\DeleteCategoryCommandHandler;
use Illuminate\Http\JsonResponse;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class DeleteCategoryPage extends AuthorizedController
{

    public function __invoke($category_id): JsonResponse
    {
        return new JsonResponse((new DeleteCategoryCommandHandler())->execute($category_id));
    }
}
