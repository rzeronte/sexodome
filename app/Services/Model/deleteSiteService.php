<?php

namespace App\Services\Model;

use App\Model\Site;

class deleteSiteService
{
    public function execute($site_id)
    {
        $site = Site::findOrFail($site_id);

        $site->delete();

        return [ 'status' => true ];
    }
}