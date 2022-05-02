<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Sexodome\SexodomeApi\Application\DeleteSiteCommandHandler;
use Illuminate\Http\RedirectResponse;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class DeleteSitePage extends AuthorizedController
{
    public function __invoke($site_id): RedirectResponse
    {
        (new DeleteSiteCommandHandler())->execute($site_id);

        return redirect()->route('sites', []);
    }
}
