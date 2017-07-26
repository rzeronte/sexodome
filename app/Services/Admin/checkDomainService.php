<?php

namespace DDD\Application\Service\Admin;

class checkDomainService
{
    public function execute($domain)
    {
        if (strlen($domain) == 0) {
            abort(404, 'Not allowed');
        }

        $sites = Site::where('domain', '=', $domain)->count();

        return json_encode(['status' => ($sites == 0) ? true : false]);
    }
}