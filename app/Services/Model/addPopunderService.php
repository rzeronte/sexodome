<?php

namespace DDD\Application\Service\Admin;

class addPopunderService
{
    public function execute($site_id, $urlPopunder)
    {
        try {
            $site = Site::findOrFail($site_id);
            $newPopunder = new Popunder();
            $newPopunder->url = $urlPopunder;
            $newPopunder->site_id = $site->id;
            $newPopunder->save();
            return json_encode(['status' => true]);
        } catch (\Exception $e) {
            return json_encode(['status' => false]);
        }

    }
}