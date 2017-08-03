<?php

namespace App\Services\Model;

use App\Model\Category;

class deleteCategoryService
{
    public function execute($category_id)
    {
        try {
            $category = Category::find($category_id);

            if (!$category) {
                return [ 'status' => false, 'message' => "Category $category_id not found" ];
            }

            $category->delete();

            return ['status' => true ];
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage() ];
        }
    }
}