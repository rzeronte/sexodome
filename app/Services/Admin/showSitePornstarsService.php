<?php

namespace DDD\Application\Service\Admin;

class showSitePornstarsService
{
    public function execute($site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return false;
        }

        $pornstars = Pornstar::where('site_id', '=', $site_id)->paginate(App::make('sexodomeKernel')->perPagePanelPornstars);

        return [
            'site'      => $site,
            'pornstars' => $pornstars,
        ];

    }
}