<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use App\Model\Popunder;
use Sexodome\Shared\Application\Command\CommandHandler;

class CreatePopunderCommandHandler implements CommandHandler
{
    public function execute($site_id, $urlPopunder)
    {
        try {
            $site = Site::find($site_id);

            if (!$site) {
                return [ 'status' => false, 'message' => "Site $site_id not found" ];
            }

            $newPopunder = new Popunder();
            $newPopunder->url = $urlPopunder;
            $newPopunder->site_id = $site->id;
            $newPopunder->save();

            return [ 'status' => true ];
        } catch (\Exception $e) {
            return [ 'status' => false , 'message' => $e->getMessage() ];
        }

    }
}
