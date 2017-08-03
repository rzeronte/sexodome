<?php

namespace App\Services\Front;

use App\Model\Pornstar;
use App\Model\Scene;

class getPornstarService
{
    public function execute($permalink, $site_id, $language_id, $per_page_pornstar_scenes, $page)
    {
        $pornstar = Pornstar::getPornstarByPermalink($permalink, $site_id);

        if (!$pornstar) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $scenes = Scene::getTranslationsForPornstar($pornstar->id, $language_id)
            ->paginate($per_page_pornstar_scenes, $columns = ['*'], $pageName = 'page', $page)
        ;

        return [
            'status' => true,
            'pornstar' => $pornstar,
            'scenes' => $scenes,
            'page' => $page
        ];
    }
}