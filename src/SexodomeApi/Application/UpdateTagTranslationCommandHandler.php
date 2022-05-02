<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\TagTranslation;
use App\Model\Tag;
use Sexodome\Shared\Application\Command\CommandHandler;

class UpdateTagTranslationCommandHandler implements CommandHandler
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
