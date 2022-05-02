<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\addPopunderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\CreatePopunderCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class SavePopunderPage extends AuthorizedController
{
    public function __invoke($site_id, Request $request): JsonResponse
    {
        return new JsonResponse((new CreatePopunderCommandHandler())->execute($site_id, Request::input('url', false)));
    }
}
