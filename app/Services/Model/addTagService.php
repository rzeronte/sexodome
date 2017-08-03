<?php

namespace App\Services\Model;

use App\Model\Site;
use App\Model\Language;
use App\Model\TagTranslation;
use App\rZeBot\rZeBotUtils;
use App\Model\Tag;

class addTagService
{
    public function execute($site_id, $languagesData)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        try {
            $newTag = new Tag();
            $newTag->site_id = $site->id;
            $newTag->status = 1;
            $newTag->save();

            foreach(Language::getAddLanguages($site->language_id) as $language) {
                $newTagTranslation = new TagTranslation();
                $newTagTranslation->tag_id = $newTag->id;
                $newTagTranslation->language_id = $language->id;
                $newTagTranslation->name = $languagesData[$language->code];
                $newTagTranslation->permalink = rZeBotUtils::slugify($languagesData[$language->code]);
                $newTagTranslation->save();
            }

            return [ 'status' => true ];
        } catch (\Exception $e) {
            return [ 'status' => false ];
        }
    }
}