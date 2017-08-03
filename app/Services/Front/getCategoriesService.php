<?php

namespace App\Services\Front;

use App\Model\Category;

class getCategoriesService
{
    public function __construct()
    {
        return $this;
    }

    public function execute($site_id, $language_id, $num_per_page, $page)
    {
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
            'categories'             => $categories,
            'categoriesAlphabetical' => $categories_extra,
            'page'                   => $page
        ];
    }
}