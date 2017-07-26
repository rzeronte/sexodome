<?php

namespace DDD\Application\Service\Admin;

class showSiteTagsService
{
    public function execute($site_id, $query_string)
    {
        $site = Site::findOrFail($site_id);

        $tags = Tag::getTranslationSearch(
            $query_string,
            $site->language->id,
            $site_id
        )->paginate(App::make('sexodomeKernel')->perPageScenes);

        return [
            'site' => $site,
            'tags' => $tags
        ];
    }
}