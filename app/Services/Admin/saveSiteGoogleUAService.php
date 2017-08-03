<?php

namespace App\Services\Admin;

class saveSiteGoogleUAService
{
    public function execute($site_id, $ga_view)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return json_encode(['status' => false, 'message' => "Site $site_id not exists"]);
        }

        try {
            $site->ga_account = $ga_view;
            $site->save();
            return [ 'status' => true ];
        } catch (\Exception $e) {
            return [ 'status' => false ];
        }
    }
}