<?php

namespace Sexodome\SexodomeTube\Application;

use App\Model\Category;
use App\Model\Scene;

class getCategoryService
{
    public function execute($permalink, $site_id, $language_id, $per_page_category_videos, $page, $order = false)
    {
        $categoryTranslation = Category::getTranslationFromPermalink($permalink, $site_id, $language_id, $status = 1);

        if (!$categoryTranslation) {
            return ['status' => false, 'message' => 'No hay categoría para el permalink indicado'];
        }

        // get scenes
        $scenes = Scene::getTranslationsForCategory(
            $categoryTranslation->category->id,
            $language_id,
            $order
        )->paginate($per_page_category_videos, $columns = ['*'], $pageName = 'page', $page);

        return [
            'status' => true,
            'scenes' => $scenes,
            'categoryTranslation' => $categoryTranslation,
            'page' => $page,
            'permalinkCategory' => $permalink
        ];
    }
}
