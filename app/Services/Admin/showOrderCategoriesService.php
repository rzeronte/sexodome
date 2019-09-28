<?php

namespace App\Services\Admin;

use App\Model\Site;
use App\Model\Category;
use Illuminate\Support\Facades\Auth;

class showOrderCategoriesService
{
    public function execute($site_id, $limit)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' =>  "Site $site_id not found"];
        }

        $categories = Category::getForTranslation(
            $status = true,
            $site->id,
            $site->language->id,
            $limit
        )->get();

        return [
            'status'     => true,
            'message'    => 'showOrderCategoriesService has been executed',
            'sites'      => Auth::user()->getSites(),
            'site'       => Site::find($site_id),
            'categories' => $categories
        ];
    }
}