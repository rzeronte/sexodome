<?php

namespace DDD\Application\Service\Admin;

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

            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}