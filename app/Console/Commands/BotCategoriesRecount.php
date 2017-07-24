<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;

class BotCategoriesRecount extends Command
{
    protected $signature = 'zbot:categories:recount {site_id}';

    protected $description = 'Update number of scenes for a category';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("[BotCategoriesRecount] site_id: $site_id not existes", "error", 'kernel');
            exit;
        }

        $categories = Category::where('site_id', '=', $site->id)->get();

        rZeBotUtils::message("[BotCategoriesRecount] Updating number scenes for " . $site->getHost(),"info",'kernel');

        $i = 0;
        foreach($categories as $category) {
            $i++;
            $countScenes = $category->scenes()->count();
            $translation = $category->translations()->where('language_id', $english = 2)->first();

            if (!$translation) {
                rZeBotUtils::message("[BotCategoriesRecount] category_id: $category->id doesn't have EN translation! | count: " . $countScenes, "error",'kernel');
                continue;
            }

            // Actualizamos solo si ha cambiado el número de escenas
            if ($category->nscenes != $countScenes) {
                $category->nscenes = $countScenes;
            }

            if ($countScenes < env('MIN_SCENES_CATEGORY_ACTIVATION')) {
                $category->status = 0;
            } else {
                $category->status = 1;
            }

            $category->save();
            rZeBotUtils::message("[BotCategoriesRecount] Recounting for $translation->name ($category->id) => count: $countScenes | nscenes bbdd: $category->nscenes", "info",'kernel');
        }
    }
}