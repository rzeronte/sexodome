<?php

namespace App\Services\Admin;

use App\Model\Site;
use App\Model\Pornstar;

class showSitePornstarsService
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
            'site'      => $site,
            'pornstars' => $pornstars,
        ];

    }
}