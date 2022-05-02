<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\CreateCronjobCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class SaveCronjobPage  extends AuthorizedController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse((new CreateCronjobCommandHandler())->execute(
            Request::input('feed_name'),
            Request::input('site_id'),
            $parameters = [
                'max' => Request::input('max'),
                'duration' => Request::input('duration'),
                'tags' => Request::input('duration'),
                'only_with_pornstars' => Request::input('only_with_pornstars') == 1 ? true : false
            ]
        ));
    }
}
