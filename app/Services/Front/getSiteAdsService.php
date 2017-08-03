<?php

namespace App\Services\Front;

use App\Model\Site;

class getSiteAdsService
{
    public function execute($site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return ['status'=>false, 'message' => 'Site not found.'];
        }

        return [
            "status" => true,
            "categories" => $site->categories()->where('status', 1)->limit(18)->get()
        ];
    }
}