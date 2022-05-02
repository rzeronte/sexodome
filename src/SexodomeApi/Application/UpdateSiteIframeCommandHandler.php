<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use Sexodome\Shared\Application\Command\CommandHandler;

class UpdateSiteIframeCommandHandler implements CommandHandler
{
    public function execute($site_id, $iframe_site_id)
    {
        try {
            $site = Site::find($site_id);

            if (!$site) {
                return [ 'status' => false, 'message' => "Site $site_id not found" ];
            }

            $site->iframe_site_id = $iframe_site_id;
            $site->save();
            return [ 'status' => true, 'message' => "Iframe for $site->domain has been updated" ];
        } catch (\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}
