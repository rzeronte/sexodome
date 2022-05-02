<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowSiteCategoriesCommandHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetSiteCategoriesPage extends AuthorizedController
{
    public function __invoke($site_id)
    {
        return view('panel.ajax._ajax_site_categories', (new ShowSiteCategoriesCommandHandler())->execute(
            $site_id,
            Request::input('q'),
            App::make('sexodomeKernel')->perPagePanelCategories,
            Request::input('order_by_nscenes', false)
        ));
    }
}
