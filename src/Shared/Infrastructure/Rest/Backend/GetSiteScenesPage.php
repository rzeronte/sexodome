<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\GetSiteScenesCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetSiteScenesPage extends AuthorizedController
{

    public function __invoke($site_id)
    {
        return view('panel.scenes', (new GetSiteScenesCommandHandler())->execute(
            $site_id,
            App::make('sexodomeKernel')->perPageScenes,
            $searchParameters = [
                'query' => Request::input('q', false),
                'tag_query' => Request::input('tag_q'),
                'duration' => Request::input('duration'),
                'category_query' => Request::input('category_string'),
                'empty_title' => Request::input('empty_title') == "on" ? true : false,
                'empty_description' => Request::input('empty_description') == "on" ? true : false,
            ]
        ));
    }
}
