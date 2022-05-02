<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\ImportScenesCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class FetchFeedPage extends AuthorizedController
{
    public function __invoke($site_id): JsonResponse
    {
        return new JsonResponse((new ImportScenesCommandHandler())->execute(
            $site_id,
            Request::input('feed_name'),
            [
                'max' => Request::input('max'),
                'duration' => Request::input('duration'),
                'tags' => Request::input('tags'),
                'only_with_pornstars' => Request::input('only_with_pornstars') == 1 ? true : false
            ]
        ));
    }
}
