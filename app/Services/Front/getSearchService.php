<?php

namespace App\Services\Front;

use App\Model\Scene;
use App\Model\Site;
use App\Model\Language;

class getSearchService
{
    public function execute($search = false, $site_id, $language_id, $per_page_search_results)
    {
        if ($per_page_search_results <= 0) {
            return [ 'status' => false, 'message' => "Invalid per page records" ];
        }

        if ($search == false) {
            return [ 'status' => false, 'message' => "Search must be a valid string" ];
        }

        if (Language::where('id', $language_id)->count() == 0) {
            return [ 'status' => false, 'message' => "Language $language_id not found" ];
        }

        if (Site::where('id', $site_id)->count() == 0) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $scenes = Scene::getTranslationSearch(
            $search,
            $language_id,
            $site_id,
            $status = true
        )->paginate($per_page_search_results);

        return [ 'status' => true, 'scenes' => $scenes , 'noindex' => true];
    }
}