<?php

namespace App\Services\Model;

use App\Model\Site;
use App\Model\Popunder;

class addPopunderService
{
    public function execute($site_id, $urlPopunder)
    {
        try {
            $site = Site::find($site_id);

            if (!$site) {
                return [ 'status' => false, 'message' => "Site $site_id not found" ];
            }

            $newPopunder = new Popunder();
            $newPopunder->url = $urlPopunder;
            $newPopunder->site_id = $site->id;
            $newPopunder->save();

            return [ 'status' => true ];
        } catch (\Exception $e) {
            return [ 'status' => false , 'message' => $e->getMessage() ];
        }

    }
}