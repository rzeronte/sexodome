<?php

namespace DDD\Application\Service\Admin;

class showCronjobsService
{
    public function execute($site_id)
    {
        $site = Site::findOrFail($site_id);

        if (!$site) {
            return false;
        }

        $scene = Scene::findOrFail($site_id);

        if (!$scene) {
            return false;
        }

        return [
            'site'  => $site,
            'scene' => $scene
        ];
    }
}