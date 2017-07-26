<?php

namespace DDD\Application\Service\Admin;

class showScenesService
{
    public function execute($site_id, $searchParameters, $per_page_scenes)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return false;
        }

        $scenes = Scene::getScenesForExporterSearch(
            $searchParameters['query'],
            $searchParameters['tag_query'],
            $site->language->id,
            $searchParameters['duration'],
            $searchParameters['scene_id'],
            $searchParameters['category_string'],
            ($searchParameters['empty_title'] == "on") ? true : false,
            ($searchParameters['empty_description'] == "on") ? true : false,
            $site->user->id,
            $site->id
        );

        return [
            'scenes' => $scenes->paginate($per_page_scenes),
        ];
    }
}