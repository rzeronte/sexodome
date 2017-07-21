<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\CategoryTranslation;
use App\Model\Language;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BotLoadJSONCategories extends Command
{
    protected $signature = 'zbot:categories:json {site_id}
                            {--gays=false : Filter for Gay categories}';

    protected $description = 'Load categories from json for one ste';

    public function handle()
    {
        $site_id = $this->argument('site_id');
        $gays = $this->option('gays');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red", false, false, 'kernel');
            return;
        }

        if ($gays !== 'false') {
            $gays = true;
        } else {
            $gays = false;
        }

        $categories = Storage::get('categories.json');
        $categories = json_decode($categories, true);
        
        rZeBotUtils::message("Load categories for ". $site->getHost(), "cyan", false, false, 'kernel');

        DB::transaction(function () use ($categories, $site, $gays) {
            $i = 0;
            foreach ($categories as $c) {
                $i++;
                $category_en = $c['text_en'];

                if ($gays == true && !str_contains(strtolower($category_en), 'gay')) {
                    rZeBotUtils::message("Jump '$category_en' for ". $site->getHost() . " -> Not Gay with --gays=true", "cyan", false, false, 'kernel');
                    continue;
                }

                if ($gays == false && str_contains(strtolower($category_en), 'gay')) {
                    rZeBotUtils::message("Jump '$category_en' for ". $site->getHost() . " -> Gay", "cyan", false, false, 'kernel');
                    continue;
                }

                rZeBotUtils::message("Loading $category_en for ". $site->getHost(), "cyan", false, false, 'kernel');

                if (Category::getTranslationByName(trim($category_en), 2, $site->id)->count() == 0) {
                    $newCategory = new Category();
                    $newCategory->status = 0;
                    $newCategory->site_id = $site->id;
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
                    rZeBotUtils::message("$i) Categoría ya existe: " . $category_en, "yellow", false, false, 'kernel');
                }
            }
        });
    }
}