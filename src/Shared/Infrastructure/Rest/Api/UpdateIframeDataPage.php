<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\UpdateSiteIframeCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class UpdateIframeDataPage  extends AuthorizedController
{

    public function __invoke($site_id): JsonResponse
    {
        return new JsonResponse((new UpdateSiteIframeCommandHandler())->execute(
            $site_id,
            (Request::input('iframe_site_id_' . $site_id) != "") ? Request::input('iframe_site_id_' . $site_id) : null
        ));
    }
}
