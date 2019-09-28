<?php

namespace App\Services\Admin;

use App\Model\CategoryTranslation;

class categoryUnlockService
{
    public function execute($category_translation_id)
    {
        $categoryTranslation = CategoryTranslation::find($category_translation_id);

        if (!$categoryTranslation) {
            return [ 'status' => false, 'message' => "CategoryTranslation $categoryTranslation not found"];
        } else {
            try {
                $categoryTranslation->thumb_locked = NULL;
                $categoryTranslation->save();

                return ['status' => true, 'message' => "CategoryTranslation($categoryTranslation->name) is locked"];

            } catch (\Exception $e) {
                return [ 'status' => false , 'message' => $e->getMessage() ];
            }
        }
    }
}