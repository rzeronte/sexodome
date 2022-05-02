<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowCategoryThumbsCommandHandler;
use Illuminate\Support\Facades\App;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetCategoryThumbnailsPage extends AuthorizedController
{

    public function __invoke($category_id)
    {
        return view('panel.ajax._ajax_category_thumbs', (new ShowCategoryThumbsCommandHandler())->execute(
            $category_id,
            App::make('sexodomeKernel')->sex_types
        ));
    }
}
