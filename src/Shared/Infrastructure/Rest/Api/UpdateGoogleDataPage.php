<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\UpdateSiteGoogleUACommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class UpdateGoogleDataPage  extends AuthorizedController
{
    public function __invoke($site_id, Request $request): JsonResponse
    {
        return new JsonResponse((new UpdateSiteGoogleUACommandHandler())->execute($site_id, Request::input('ga_view_' . $site_id)));
    }
}
