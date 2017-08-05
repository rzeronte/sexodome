<?php

namespace App\Services\Front;

use App\Model\Category;
use App\Model\Site;
use App\Model\Language;

class getCategoriesService
{
    public function __construct()
    {
        return $this;
    }

    public function execute($site_id, $language_id, $num_per_page, $page)
    {
        if ($num_per_page <= 0) {
            return [ 'status' => false, 'message' => "Invalid per page records" ];
        }

        if ($page <= 0) {
            return [ 'status' => false, 'message' => "Invalid page" ];
        }

        if (Site::where('id', $site_id)->count() == 0) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        if (Language::where('id', $language_id)->count() == 0) {
            return [ 'status' => false, 'message' => "Language $language_id not found" ];
        }

        $categories = Category::getForTranslation(
            $status = true,
            $site_id,
            $language_id
        )->paginate($num_per_page, $columns = ['*'], $pageName = 'page', $page);

        $categories_extra = Category::getForTranslation(
            $status = true,
            $site_id,
            $language_id,
            $limit = 120
        )->get();

        return [
            'status' => true,
            'categories' => $categories,
            'categoriesAlphabetical' => $categories_extra,
            'page' => $page
        ];
    }
}