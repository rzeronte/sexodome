<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use App\Model\Pornstar;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowSitePornstarsCommandHandler implements CommandHandler
{
    public function execute($site_id, $perPage)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $pornstars = Pornstar::where('site_id', '=', $site_id)->paginate($perPage);

        return [
            'status'    => true,
            'message'   => 'showSitePornstarsCommandHandler has been executed',
            'site'      => $site,
            'pornstars' => $pornstars,
        ];

    }
}
