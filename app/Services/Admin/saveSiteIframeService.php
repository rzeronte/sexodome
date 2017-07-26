<?php

namespace DDD\Application\Service\Admin;

class saveSiteIframeService
{
    public function execute($site_id, $iframe_site_id)
    {
        try {
            $site = Site::findOrFail($site_id);
            $site->iframe_site_id = $iframe_site_id;
            $site->save();
            return json_encode(['status' => true]);
        } catch (\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}