<?php

namespace DDD\Application\Service\Admin;

class showSiteCategoriesService
{
    public function execute($site_id, $query_string, $order = false)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return false;
        }

        $categories = Category::getTranslationSearch(
            $query_string,
            $site->language->id,
            $site->id,
            $order
        )->paginate(30);

        return [
            'categories' => $categories,
        ];
    }
}