<?php

namespace App\Services\Model;

use App\Model\Category;

class saveCategoryTagsService
{
    public function execute($category_id, $tag_ids)
    {
        $category = Category::find($category_id);

        if ($category) {
            $category->tags()->sync($tag_ids);

            return [ 'status' => true ];
        } else {
            return [ 'status' => false, 'message' => 'Category not found' ];
        }
    }
}