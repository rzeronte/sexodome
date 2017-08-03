<?php

namespace App\Services\Admin;

use App\Model\Site;
use App\Model\Category;

class showSiteCategoriesService
{
    public function execute($site_id, $query_string, $perPage, $order = false)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $categories = Category::getTranslationSearch(
            $query_string,
            $site->language->id,
            $site->id,
            $order
        )->paginate($perPage);

        return [
            'site' => $site,
            'categories' => $categories,
        ];
    }
}