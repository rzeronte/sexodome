<?php

namespace App\Console\Commands;

use App\Model\Language;
use App\Model\Tag;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;

class rZeBotDuplicateLanguage extends Command{
    var $creation;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:duplicate:language {from} {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch rZeBot for duplicate language';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from = $this->argument('from');
        $to   = $this->argument('to');

        $languageFrom   = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        echo PHP_EOL."Duplicating tags from " . $from ." to " . $to.PHP_EOL;

        $tags = Tag::all();

        foreach($tags as $tag) {

            $translationFrom = $tag->translations()->where('language_id', $languageFrom->id)->first();
            $translationTo   = $tag->translations()->where('language_id', $languageTo->id)->first();

            if ($translationFrom) {
                if (!$translationTo) {
                    echo "Add translation for " .$tag->id.PHP_EOL;
                    $newTranslation = new TagTranslation();
                    $newTranslation->tag_id = $tag->id;
                    $newTranslation->language_id = $languageTo->id;
                    $newTranslation->name = $translationFrom->name;
                    $newTranslation->permalink = $translationFrom->permalink;
                    $newTranslation->save();
                } else {
                    echo "Update translation for " .$tag->id.PHP_EOL;
                    $translationTo->name = $translationTo->name;
                    $translationTo->permalink = $translationTo->permalink;
                    $translationTo->save();
                }

            }
        }
    }
}
