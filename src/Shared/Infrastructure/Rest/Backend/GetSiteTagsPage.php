<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowSiteTagsCommandHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetSiteTagsPage extends AuthorizedController
{

    public function __invoke($site_id, Request $request)
    {
        return view('panel.ajax._ajax_site_tags', (new ShowSiteTagsCommandHandler())->execute(
            $site_id,
            Request::input('q'),
            App::make('sexodomeKernel')->perPageTags
        ));
    }
}
