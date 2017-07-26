<?php

namespace DDD\Application\Service\Admin;

class addCategoryService
{
    public function execute($site_id, Request $request)
    {
        try {
            $site = Site::findOrFail($site_id);

            $newCategory = new Category();
            $newCategory->site_id = $site->id;
            $newCategory->status = 0;
            $newCategory->text = $request->input('language_en');
            $newCategory->save();

            foreach(Language::getAddLanguages($site->language_id) as $language) {
                $newCategoryTranslation = new CategoryTranslation();
                $newCategoryTranslation->category_id = $newCategory->id;
                $newCategoryTranslation->language_id = $language->id;
                $newCategoryTranslation->name = $request->input('language_'.$language->code);
                $newCategoryTranslation->permalink = rZeBotUtils::slugify($request->input('language_'.$language->code));
                $newCategoryTranslation->save();
            }

            return json_encode(['status' => true]);

        } catch(\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}