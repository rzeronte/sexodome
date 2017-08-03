<?php

namespace App\Services\Front;

use App\Model\Scene;

class getSearchService
{
    public function execute($search = false, $site_id, $language_id, $per_page_search_results)
    {
        $scenes = Scene::getTranslationSearch(
            $search,
            $language_id,
            $site_id,
            $status = true
        )->paginate($per_page_search_results);

        return [ 'status' => true, 'scenes' => $scenes ];
    }
}