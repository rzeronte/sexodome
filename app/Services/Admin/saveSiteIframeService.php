<?php

namespace App\Services\Admin;

class saveSiteIframeService
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
            return [ 'status' => true ];
        } catch (\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}