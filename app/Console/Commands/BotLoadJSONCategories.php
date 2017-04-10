<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\CategoryTranslation;
use App\Model\Language;
use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Scene;
use DB;
use Illuminate\Support\Facades\Storage;

class BotLoadJSONCategories extends Command
{
    protected $signature = 'zbot:categories:json {site_id}';

    protected $description = 'Load categories from json for one ste';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");
            return;
        }

        if (!$this->ask('Do you want load JSON categories in ' . $site->getHost() . "?")) {
            return;
        }

        $categories = Storage::get('categories.json');
        $categories = \GuzzleHttp\json_decode($categories, true);
        
        $i = 0;
        rZeBotUtils::message("Load categories for ". $site->getHost(), "cyan", false, false);
        foreach ($categories as $c) {
            $i++;
            $category_en = $c['text_en'];
            rZeBotUtils::message("Loading $category_en for ". $site->getHost(), "cyan", false, false);

            if (Category::getTranslationByName(trim($category_en), 2, $site->id)->count() == 0) {
                $newCategory = new Category();
                $newCategory->status = 0;
                $newCategory->site_id = $site_id;
                $newCategory->text = $category_en;
                $newCategory->nscenes = 0;
                $newCategory->save();

                foreach(Language::all() as $language) {
                    if (isset($c['text_'.$language->code])) {
                        $newCategoryTranslation = new CategoryTranslation();
                        $newCategoryTranslation->category_id = $newCategory->id;
                        $newCategoryTranslation->language_id = $language->id;
                        $newCategoryTranslation->name = $c['text_'.$language->code];
                        $newCategoryTranslation->permalink = str_slug($c['text_'.$language->code]);
                        $newCategoryTranslation->save();
                    }
                }
            } else {
                $this->error("$i) Categoría ya existe: " . $category_en);
            }
        }
    }
}