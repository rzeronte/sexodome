<?php

namespace DDD\Application\Service\Admin;

class saveCategoryTagsService
{
    public function execute($category_id, $tag_ids)
    {
        $category = Category::find($category_id);

        if ($category) {
            $category->tags()->sync($tag_ids);

            return json_encode(['status' => true]);
        } else {

            return json_encode(['status' => false, 'message' => 'Category not found']);
        }
    }
}