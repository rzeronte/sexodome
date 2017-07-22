<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use Illuminate\Support\Facades\DB;

class BotUpdateCategoriesThumbnails extends Command
{
    protected $signature = 'zbot:categories:thumbnails
                            {--site_id=false : Only update for a site_id}
                            {--ignore_locked=false : Ignore thumb locked}';

    protected $description = 'Update category thumbnails for all sites';

    public function handle()
    {
        $ignore_locked = $this->option('ignore_locked');
        $site_id = $this->option('site_id');

        if ($ignore_locked !== 'false') {
            $ignore_locked = true;
        } else {
            $ignore_locked = false;
        }

        if ($site_id !== "false") {

            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("[BotUpdateCategoriesThumbnails] El site id: $site_id no existe", "error",'kernel');
                exit;
            }

            $sites = Site::where('id', $site_id)->get();

        } else {
            $sites = Site::all();
        }

        DB::transaction(function () use ($sites, $ignore_locked) {

            foreach ($sites as $site) {
                $categories = Category::where('site_id', '=', $site->id)->get();

                rZeBotUtils::message("[BotUpdateCategoriesThumbnails] Updating thumbnails for '" . $site->getHost() . "'", "warning",'kernel');

                $scenes_id_used = [];
                foreach($categories as $category) {
                    $scene_id = Category::updateCategoryThumbnail($category, $scenes_id_used, $ignore_locked);
                    if ($scene_id) {
                        $scenes_id_used[] = $scene_id;
                    }
                }
            }
        });
    }
}