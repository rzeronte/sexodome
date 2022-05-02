<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\DeleteTagCommandHandler;
use Illuminate\Http\JsonResponse;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class DeleteTagPage extends AuthorizedController
{
    public function __invoke($tag_id): JsonResponse
    {
        return new JsonResponse((new DeleteTagCommandHandler())->execute($tag_id));
    }
}
