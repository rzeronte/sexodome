<?php

namespace DDD\Application\Service\Admin;

class categoryUnlockService
{
    public function execute($category_translation_id)
    {
        $categoryTranslation = CategoryTranslation::find($category_translation_id);

        if (!$categoryTranslation) {

            return json_encode(['status' => true]);

        } else {
            try {
                $categoryTranslation->thumb_locked = NULL;
                $categoryTranslation->save();

                return json_encode(['status' => true]);

            } catch (\Exception $e) {
                return json_encode(['status' => false]);
            }
        }
    }
}