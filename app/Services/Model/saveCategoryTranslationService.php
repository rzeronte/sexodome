<?php

namespace App\Services\Model;

use App\Model\CategoryTranslation;
use App\Model\Category;

class saveCategoryTranslationService
{
    public function execute($category_id, $language_id, $name, $thumb, $status)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return ['status' => false, 'message' => 'Category not exists'];
        }

        $site = $category->site;

        // Buscamos si existe otra categoría en el idioma del site con el mismo nombre
        $alreadyCategoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
            ->where('categories.site_id', $category->site->id)
            ->where('language_id', $language_id)
            ->where('name', 'like', $name)
            ->where('categories.status', 1)
            ->where('categories.id', '<>', $category_id)
            ->first()
        ;

        if ($alreadyCategoryTranslation) {
            return ['status' => false, 'message' => 'CategoryTranslation not found'];
        }

        $categoryTranslation = CategoryTranslation::where('category_id', $category_id)
            ->where('language_id', $site->language->id)
            ->first()
        ;

        $categoryTranslation->name = $name;
        $categoryTranslation->permalink = str_slug($name);
        $categoryTranslation->thumb = $thumb;
        $categoryTranslation->thumb_locked = true;
        $categoryTranslation->save();

        $category->status = $status;
        $category->save();

        return ['status' => true, 'thumb' => $thumb];
    }
}