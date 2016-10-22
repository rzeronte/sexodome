<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use App\Model\Scene;

class BotUpdateCategoriesThumbnails extends Command
{
    protected $signature = 'zbot:categories:thumbnails
                            {--site_id=false : Only update for a site_id}';

    protected $description = 'Update category thumbnails for all sites';

    public function handle()
    {
        $site_id = $this->option('site_id');

        if ($site_id !== "false") {

            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("Error el site id: $site_id no existe", "red");
                exit;
            }

        } else {
            $sites = Site::all();
        }

        foreach ($sites as $site) {
            $categories = Category::where('site_id', '=', $site->id)->get();

            rZeBotUtils::message("[UPDATE THUMBNAILS] " . $site->getHost(), "yellow", false, false);

            $scenes_id_used = [];
            foreach($categories as $category) {
                $scene_id = rZeBotUtils::updateCategoryThumbnail($category, $scenes_id_used);
                if ($scene_id) {
                    $scenes_id_used[] = $scene_id;
                }
            }
        }
    }
}