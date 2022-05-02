<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\GetSitesCommandHandler;
use Illuminate\Support\Facades\Auth;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetSitesPage extends AuthorizedController
{

    public function __invoke()
    {
        return view('panel.sites', (new GetSitesCommandHandler())->execute(Auth::user()->id));
    }
}
