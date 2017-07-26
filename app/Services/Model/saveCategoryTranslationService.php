<?php

namespace DDD\Application\Service\Admin;

class saveCategoryTranslationService
{
    public function execute($category_id, $name, $thumb, $status)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return json_encode(['status' => false, 'message' => 'Category not exists']);
        }

        $site = $category->site;

        // Buscamos si existe otra categoría en el idioma del site con el mismo nombre
        $alreadyCategoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
            ->where('categories.site_id', $category->site->id)
            ->where('language_id', $site->language->id)
            ->where('name', 'like', $name)
            ->where('categories.status', 1)
            ->where('categories.id', '<>', $category_id)
            ->first()
        ;

        if ($alreadyCategoryTranslation) {
            return json_encode(['status' => false]);
        }

        $categoryTranslation = CategoryTranslation::where('category_id', $category_id)
            ->where('language_id', $site->language->id)
            ->first()
        ;

        $categoryTranslation->name = $name;
        $categoryTranslation->permalink = str_slug($name);
        $categoryTranslation->thumb = $thumb;
        $categoryTranslation->save();

        $category->status = $status;
        $category->save();

        return json_encode(['status' => true]);
    }
}