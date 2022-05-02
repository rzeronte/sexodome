<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\UpdateOrderCategoriesCommandHandler;
use Sexodome\SexodomeApi\Application\ShowOrderCategoriesCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetOrderCategoriesPage  extends AuthorizedController
{
    public function __invoke($site_id)
    {
        return view('panel.categories_order', (new ShowOrderCategoriesCommandHandler())->execute($site_id, 100));
    }
}
