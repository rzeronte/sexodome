<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\CheckDomainCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CheckDomainPage extends AuthorizedController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse((new CheckDomainCommandHandler())->execute(Request::input('domain')));
    }
}
