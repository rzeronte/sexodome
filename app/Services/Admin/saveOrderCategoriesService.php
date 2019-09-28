<?php

namespace App\Services\Admin;

use App\Model\Category;
use Illuminate\Support\Facades\DB;

class saveOrderCategoriesService
{
    public function execute($site_id, $orderData)
    {
        try {
            DB::table('categories')->where('site_id', $site_id)->update(['cache_order' => -999999]);

            foreach ($orderData as $category) {
                $categoyBBDD = Category::find($category['i']);
                $categoyBBDD->cache_order = -1 * $category['o'];
                $categoyBBDD->save();
            }

            return [ 'status' => true , 'message' => 'OrderCategories updated succesfuly'];
        } catch(\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}