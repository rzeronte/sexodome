<?php

namespace Sexodome\SexodomeTube\Application;

use App\Model\Pornstar;

class getPornstarsService
{
    public function execute($site_id, $per_page_pornstars, $page)
    {
        $pornstars = Pornstar::where('site_id', $site_id)
            ->paginate($per_page_pornstars, $columns = ['*'], $pageName = 'page', $page)
        ;

        return [
            'status' => true,
            'pornstars' => $pornstars,
            'page' => $page
        ];
    }
}
