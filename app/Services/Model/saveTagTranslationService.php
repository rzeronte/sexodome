<?php

namespace App\Services\Model;

use App\Model\TagTranslation;
use App\Model\Tag;

class saveTagTranslationService
{
    public function execute($tag_id, $language_id, $name, $status)
    {
        $tagTranslation = TagTranslation::where('tag_id', $tag_id)
            ->where('language_id', $language_id)
            ->first()
        ;

        $tagTranslation->name = $name;
        $tagTranslation->permalink = str_slug($name);
        $tagTranslation->save();

        try {
            $tag = Tag::findOrFail($tag_id);
            $tag->status = $status;
            $tag->save();
            return ['status' => true];

        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }

    }
}