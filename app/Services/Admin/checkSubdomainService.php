<?php

namespace DDD\Application\Service\Admin;

class checkSubdomainService
{
    public function execute($subdomain)
    {
        if (strlen($subdomain) == 0) {
            abort(406, 'Not acceptable');
        }

        $sites = Site::where('name', '=', $subdomain)->count();

        return json_encode(['status' => ($sites == 0) ? true : false]);
    }
}