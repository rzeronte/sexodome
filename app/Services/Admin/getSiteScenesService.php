<?php

namespace App\Services\Admin;

use App\Model\Scene;
use App\Model\Site;

class getSiteScenesService
{
    public function execute($site_id, $per_page_scenes, $searchParameters)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return ['status' => false, 'message' => 'Site not found'];
        }

        $scenes = Scene::getScenesForExporterSearch(
            $searchParameters['query'],
            $searchParameters['tag_query'],
            $site->language->id,
            $searchParameters['duration'],
            $searchParameters['category_query'],
            ($searchParameters['empty_title'] == "on") ? true : false,
            ($searchParameters['empty_description'] == "on") ? true : false,
            $site->user->id,
            $site->id
        );

        return [
            'status' => true,
            'site'   => $site,
            'scenes' => $scenes->paginate($per_page_scenes),
        ];
    }
}