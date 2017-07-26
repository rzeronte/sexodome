<?php

namespace DDD\Application\Service\Admin;

class saveSiteColorsService
{
    public function execute($site_id, $parameters)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return json_encode(['status' => false, 'message' => 'Site not found']);
        }

        $site->color   = $parameters['color'] != "" ? $parameters['color'] : null;
        $site->color2  = $parameters['color2'] != "" ? $parameters['color2'] : null;
        $site->color3  = $parameters['color3'] != "" ? $parameters['color3'] : null;
        $site->color4  = $parameters['color4'] != "" ? $parameters['color4'] : null;
        $site->color5  = $parameters['color5'] != "" ? $parameters['color5'] : null;
        $site->color6  = $parameters['color6'] != "" ? $parameters['color6'] : null;
        $site->color7  = $parameters['color7'] != "" ? $parameters['color7'] : null;
        $site->color8  = $parameters['color8'] != "" ? $parameters['color8'] : null;
        $site->color9  = $parameters['color9'] != "" ? $parameters['color9'] : null;
        $site->color10 = $parameters['color10'] != "" ? $parameters['color10'] : null;
        $site->color11 = $parameters['color11'] != "" ? $parameters['color11'] : null;
        $site->color12 = $parameters['color12'] != "" ? $parameters['color12'] : null;
        $site->save();

        Artisan::call('zbot:css:update', ['--site_id' => $site->id]);

        return json_encode(['status' => true]);
    }
}