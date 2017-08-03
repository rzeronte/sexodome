<?php

namespace App\Services\Admin;

use App\Model\Site;

class showSiteCronjobsService
{
    public function execute($site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found"];
        }

        return [
            'status' => true,
            'site'   => $site,
        ];
    }
}