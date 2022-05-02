<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\UpdateOrderCategoriesCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class SaveOrderCategoriesPage extends AuthorizedController
{
    public function __invoke($site_id)
    {
        if (Request::input('o') != "") {
            return new JsonResponse((new UpdateOrderCategoriesCommandHandler())->execute($site_id, Request::input('o')));
        }
    }
}
