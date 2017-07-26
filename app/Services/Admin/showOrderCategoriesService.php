<?php

namespace DDD\Application\Service\Admin;

class showOrderCategoriesService
{
    public function execute($site_id, $orderData = null)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return false;
        }


        $categories = Category::getForTranslation(
                $status = true,
                $site->id,
                $site->language->id,
                $limit = 40
        )->get();

        return [
            'sites'      => Auth::user()->getSites(),
            'site'       => Site::find($site_id),
            'categories' => $categories
        ];
    }
}