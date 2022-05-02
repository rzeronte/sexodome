<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowCategoryTagsCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetCategoryTagsPage extends AuthorizedController
{
    public function __invoke($category_id)
    {
        return view('panel.ajax._ajax_category_tags', (new ShowCategoryTagsCommandHandler())->execute($category_id));
    }
}
