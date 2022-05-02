<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\CreateSiteCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CreateSitePage extends AuthorizedController
{
    public function __invoke()
    {
        return view('panel.add_site', (new CreateSiteCommandHandler())->execute(Request::input('domain')));
    }
}
