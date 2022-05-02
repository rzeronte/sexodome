<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use App\rZeBot\sexodomeKernel;
use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\UploadSiteLogoCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class UpdateLogoPage extends AuthorizedController
{
    public function __invoke($site_id): JsonResponse
    {
        return new JsonResponse((new UploadSiteLogoCommandHandler())->execute(
            $site_id,
            sexodomeKernel::getLogosFolder(),
            sexodomeKernel::getFaviconsFolder(),
            sexodomeKernel::getHeadersFolder()
        ));
    }
}
