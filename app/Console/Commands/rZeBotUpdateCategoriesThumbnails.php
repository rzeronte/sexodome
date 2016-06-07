<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use App\Model\Scene;

class rZeBotUpdateCategoriesThumbnails extends Command
{
    protected $signature = 'rZeBot:categories:thumbnails {site_id}';

    protected $description = 'Actualiza las thumbs de las categorías';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe");
            exit;
        }

        $categories = Category::where('site_id', '=', $site->id)->get();

        rZeBotUtils::message("Actualizando thumbnails para el sitio " . $site->getHost(), "green");

        foreach($categories as $category) {
            foreach ($category->translations()->where('language_id', $site->language_id)->get() as $translation) {
                rZeBotUtils::message("Actualizando thumbnail para la categoría $category->id, Lang: $translation->language_id: $translation->name", "green");
                $translation->thumb = $category->scenes()->orderByRaw("RAND()")->first()->preview;
                $translation->save();
            }
        }
    }
}