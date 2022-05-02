<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use Sexodome\Shared\Application\Command\CommandHandler;

class DeleteSiteCommandHandler implements CommandHandler
{
    public function execute($site_id)
    {
        $site = Site::findOrFail($site_id);

        $site->delete();

        return [ 'status' => true ];
    }
}
