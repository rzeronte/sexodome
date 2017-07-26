<?php

namespace DDD\Application\Service\Admin;

class addTagService
{
    public function execute($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

        try {
            $newTag = new Tag();
            $newTag->site_id = $site->id;
            $newTag->status = 1;
            $newTag->save();

            foreach(Language::getAddLanguages($site->language_id) as $language) {
                $newTagTranslation = new TagTranslation();
                $newTagTranslation->tag_id = $newTag->id;
                $newTagTranslation->language_id = $language->id;
                $newTagTranslation->name = $request->input('language_'.$language->code);
                $newTagTranslation->permalink = rZeBotUtils::slugify($request->input('language_'.$language->code));
                $newTagTranslation->save();
            }

            return json_encode(['status' => true]);
        } catch (\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}