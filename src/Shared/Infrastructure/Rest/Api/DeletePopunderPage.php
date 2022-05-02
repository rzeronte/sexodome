<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\DeletePopunderCommandHandler;
use Illuminate\Http\JsonResponse;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class DeletePopunderPage extends AuthorizedController
{

    public function __invoke($popunder_id): JsonResponse
    {
        return new JsonResponse((new DeletePopunderCommandHandler())->execute($popunder_id));
    }
}
