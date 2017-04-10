<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use App\Model\Scene;

class BotCategoriesRecount extends Command
{
    protected $signature = 'zbot:categories:recount {site_id}';

    protected $description = 'Update number of scenes for a category';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");
            exit;
        }

        $categories = Category::where('site_id', '=', $site->id)->get();

        rZeBotUtils::message("Actualizando thumbnails para el sitio " . $site->getHost(), "cyan");

        $i = 0;
        foreach($categories as $category) {
            $i++;
            $countScenes = $category->scenes()->count();
            $translation = $category->translations()->where('language_id', $english = 2)->first();

            if (!$translation) {
                rZeBotUtils::message("[$i][ERROR] $category->id no tiene traducción al inglés! | count: " . $countScenes, "red");
                continue;
            }

            if ($category->nscenes != $countScenes) {
                $category->nscenes = $countScenes;
                if ($countScenes < env('MIN_SCENES_CATEGORY_ACTIVATION')) {
                    $category->status = 0;
                } else {
                    $category->status = 1;
                }
                $category->save();
                rZeBotUtils::message("[$i][SUCCESS] $translation->name ($category->id) => count: $countScenes | nscenes bbdd: $category->nscenes", "yellow");
            } else {
                if ($countScenes < env('MIN_SCENES_CATEGORY_ACTIVATION')) {
                    $category->status = 0;
                } else {
                    $category->status = 1;
                }
                $category->save();

                rZeBotUtils::message("[$i][SUCCESS] $translation->name ($category->id) => count: $countScenes | nscenes bbdd: $category->nscenes", "green");
            }
        }
    }
}