<?php

namespace App\Services\Admin;

use App\Model\Site;

class checkDomainService
{
    public function execute($domain)
    {
        if (strlen($domain) == 0) {
            return [ 'status' => false, 'message' => 'Domain too short!'];
        }

        $sites = Site::where('domain', '=', $domain)->count();

        if ($sites == 0) {
            return [
                'status'  => true,
                'message' => 'Domain is available'
            ];
        } else {
            return [
                'status'  => false,
                'message' => 'Domain not available'
            ];
        }
    }
}