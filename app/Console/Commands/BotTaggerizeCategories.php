<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Tag;
use DB;

class BotTaggerizeCategories extends Command
{
    protected $signature = 'zbot:categories:taggerize {site_id}';

    protected $description = 'Taggerize categories for a site';

    public function handle()
    {
        $site_id = $this->argument('site_id');
        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");
            return;
        }

        $categories = $site->categories()->get();
        foreach ($categories as $category) {
            $category_txt = $category->translations()->where('language_id', 2)->first()->name;

            $category_tags_ids = [];
            if (Tag::getTranslationSearch($category_txt, 2, $site_id)->count() > 0) {
                foreach (Tag::getTranslationSearch($category_txt, 2, $site_id)->get() as $tag) {
                    $category_tags_ids[] = $tag->id;
                }
            }

            // Asociamos los nuevos ids, con los que ya había
            $total_ids = array_unique(array_merge($category->tags()->get()->pluck('id')->all(), $category_tags_ids));
            rZeBotUtils::message("Asociando la categoría $category_txt con " . count($total_ids) . " categorías", "green", false, false);
            $category->tags()->sync($total_ids);
        }
    }
}