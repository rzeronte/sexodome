<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\ShowSitePornstarsCommandHandler;
use Illuminate\Support\Facades\App;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class GetSitePornstarsPage extends AuthorizedController
{
    public function __invoke($site_id)
    {
        return view('panel.ajax._ajax_site_pornstars', (new ShowSitePornstarsCommandHandler())->execute(
            $site_id,
            App::make('sexodomeKernel')->perPagePanelPornstars
        ));
    }
}
