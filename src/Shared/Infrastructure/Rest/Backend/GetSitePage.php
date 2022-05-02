<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\GetSiteCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetSitePage extends AuthorizedController
{

    public function __invoke($site_id)
    {
        return view('panel.site', (new GetSiteCommandHandler())->execute($site_id));
    }
}
