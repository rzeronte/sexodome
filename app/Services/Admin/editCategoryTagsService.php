<?php

namespace DDD\Application\Service\Admin;

class editCategoryTagsService
{
    public function execute($category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return false;
        }

        $category_tags = Tag::getTranslationByCategory($category, 2)->get()->pluck('id');
        $category_tags = $category_tags->all();

        $site_tags = Tag::getTranslationSearch(false, 2, $category->site->id)->orderBy('permalink', 'asc')->get();

        return [
            'category'      => $category,
            'category_tags' => $category_tags,
            'tags'          => $site_tags,
        ];
    }
}