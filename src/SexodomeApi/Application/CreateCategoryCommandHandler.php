<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use App\Model\Language;
use App\Model\Category;
use App\Model\CategoryTranslation;
use App\rZeBot\rZeBotUtils;
use Sexodome\Shared\Application\Command\CommandHandler;

class CreateCategoryCommandHandler implements CommandHandler
{
    public function execute($site_id, $general_text, $languagesData)
    {
        try {
            $site = Site::find($site_id);

            if (!$site) {
                return [ 'status' => false, 'message' => "Site $site_id not found" ];
            }

            $newCategory = new Category();
            $newCategory->site_id = $site->id;
            $newCategory->status = 0;
            $newCategory->text = $general_text;
            $newCategory->save();

            foreach(Language::getAddLanguages($site->language_id) as $language) {
                if (isset($languagesData[$language->code])) {
                    $newCategoryTranslation = new CategoryTranslation();
                    $newCategoryTranslation->category_id = $newCategory->id;
                    $newCategoryTranslation->language_id = $language->id;
                    $newCategoryTranslation->name = $languagesData[$language->code];
                    $newCategoryTranslation->permalink = rZeBotUtils::slugify($languagesData[$language->code]);
                    $newCategoryTranslation->save();
                } else {
                    return [ 'status' => false, 'message' => "Missing data in languages." ];
                }
            }

            return [ 'status' => true ];
        } catch(\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}
