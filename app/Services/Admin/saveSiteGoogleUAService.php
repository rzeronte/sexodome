<?php

namespace App\Services\Admin;

use App\Model\Site;

class saveSiteGoogleUAService
{
    public function execute($site_id, $ga_view)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return ['status' => false, 'message' => "Site $site_id not exists"];
        }

        try {
            $site->ga_account = $ga_view;
            $site->save();
            return [ 'status' => true, 'message' => "GoogleUA for $site->domain has been updated" ];
        } catch (\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}