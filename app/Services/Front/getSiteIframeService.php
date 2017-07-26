<?php

namespace App\Services\Front;

use App\Model\Site;

class getSiteIframeService
{
    public function execute($site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return ['status'=>false, 'message' => 'Site not found.'];
        }

        return $site->categories()->where('status', 1)->limit(18)->get();
    }
}