<?php

namespace App\Console\Commands;

use App\Model\Category;
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
    protected $signature = 'zbot:categories:json
                                {--site_id=false : Categorize only for a site_id}';


    protected $description = 'Load categories from json for one or all sites';

    public function handle()
    {
        $site_id = $this->option('site_id');

        if ($site_id !== "false") {
            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("Error el site id: $site_id no existe", "red");
                exit;
            }

            $sites = Site::where('id', $site_id)->get();

        } else {
            $sites = Site::all();
        }

        $categories = Storage::get('categories.json');
        $categories = \GuzzleHttp\json_decode($categories, true);
        
        foreach($sites as $site) {
            $i = 0;
            rZeBotUtils::message("Load categories for ". $site->getHost(), "cyan", false, false);
            foreach ($categories as $c) {
                $i++;
                $category_en = $c['text_en'];
                if (Category::getTranslationByName(trim($category_en), 2, $site->id)->count() == 0) {
                    $this->info("$i) Alta de categoría: " . $category_en );
                } else {
                    $this->error("$i) Categoría ya existe: " . $category_en);
                }
            }
        }
    }
}