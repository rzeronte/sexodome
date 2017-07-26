<?php

namespace DDD\Application\Service\Admin;

class showSitePopundersService
{
    public function execute($site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return false;
        }

        return [
            'popunders' => $site->popunders()->get()
        ];
    }
}