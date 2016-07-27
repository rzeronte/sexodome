<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use App\Model\Scene;

class BotUpdateCategoriesThumbnails extends Command
{
    protected $signature = 'zbot:categories:thumbnails {site_id}';

    protected $description = 'Update category thumbnails for a site';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");
            exit;
        }

        $categories = Category::where('site_id', '=', $site->id)->get();

        rZeBotUtils::message("Actualizando thumbnails para el sitio " . $site->getHost(), "yellow");

        foreach($categories as $category) {
            foreach ($category->translations()->where('language_id', $site->language_id)->get() as $translation) {
                $img = $category->scenes()->orderByRaw("RAND()")->first()->preview;
                rZeBotUtils::message("[UPDATE] $category->id: $img, Lang: $translation->language_id: $translation->name", "green");
                $translation->thumb = $img;
                $translation->save();
            }
        }
    }
}