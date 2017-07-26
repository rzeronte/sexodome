<?php

namespace DDD\Application\Service\Admin;

class saveSiteGoogleUAService
{
    public function execute($site_id, $ga_view)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return json_encode(['status' => false, 'message' => 'Site not exists']);
        }

        try {
            $site->ga_account = $ga_view;
            $site->save();
            return json_encode(['status' => true]);
        } catch (\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}