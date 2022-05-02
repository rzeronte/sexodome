<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowSiteCronjobsCommandHandler implements CommandHandler
{
    public function execute($site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found"];
        }

        return [
            'status'  => true,
            'message' => 'showSiteCronjobsCommandHandler has been executed',
            'site'    => $site,
        ];
    }
}
