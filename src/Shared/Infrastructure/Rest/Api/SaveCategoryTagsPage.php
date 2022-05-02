<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\UpdateCategoryTagsCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class SaveCategoryTagsPage  extends AuthorizedController
{

    public function __invoke($category_id)
    {
        return new JsonResponse((new UpdateCategoryTagsCommandHandler())->execute($category_id, Request::input('categories')));
    }
}
