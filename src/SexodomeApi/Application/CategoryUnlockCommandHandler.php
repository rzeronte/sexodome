<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\CategoryTranslation;
use Sexodome\Shared\Application\Command\CommandHandler;

class CategoryUnlockCommandHandler implements CommandHandler
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
