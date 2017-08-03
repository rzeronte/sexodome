<?php

namespace App\Services\Admin;

use App\Model\Site;
use App\Model\Tag;

class showSiteTagsService
{
    public function execute($site_id, $query_string, $perPage)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $tags = Tag::getTranslationSearch(
            $query_string,
            $site->language->id,
            $site_id
        )->paginate($perPage);

        return [
            'status' => true,
            'site'   => $site,
            'tags'   => $tags
        ];
    }
}