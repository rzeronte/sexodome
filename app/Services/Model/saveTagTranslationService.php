<?php

namespace DDD\Application\Service\Admin;

class saveTagTranslationService
{
    public function execute($tag_id, $name, $status)
    {
        $tagTranslation = TagTranslation::where('tag_id', $tag_id)
            ->where('language_id', App::make('sexodomeKernel')->language->id)
            ->first()
        ;

        $tagTranslation->name = $name;
        $tagTranslation->permalink = str_slug($name);
        $tagTranslation->save();

        try {
            $tag = Tag::findOrFail($tag_id);
            $tag->status = $status;
            $tag->save();
            return json_encode(['status' => true]);

        } catch (\Exception $e) {
            return json_encode(['status' => false]);
        }

    }
}