<?php

namespace DDD\Application\Service\Admin;

class deleteCategoryService
{
    public function execute($category_id)
    {
        try {
            $category = Category::findOrFail($category_id);
            $category->delete();
            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}