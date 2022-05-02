<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\DeleteCronjobCommandHandler;
use Illuminate\Http\JsonResponse;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class DeleteCronjobPage extends AuthorizedController
{

    public function __invoke($cronjob_id): JsonResponse
    {
        return new JsonResponse((new DeleteCronjobCommandHandler())->execute($cronjob_id));
    }
}
