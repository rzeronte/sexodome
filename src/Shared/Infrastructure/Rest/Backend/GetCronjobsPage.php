<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowSiteCronjobsCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetCronjobsPage extends AuthorizedController
{
    public function __invoke($site_id)
    {
        return view('panel.ajax._ajax_site_cronjobs', (new ShowSiteCronjobsCommandHandler())->execute($site_id));
    }
}
