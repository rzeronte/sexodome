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
                            {--ignore_locked=false : Update category ignoring lock}
                            {--ignore_no_videos=false : Update even if there are no videos}
                            {--ignore_no_videos=false : Update even if there are no videos}
                            {--category_id=false : Only update for this category}
                            ';

    protected $description = 'Update category thumbnails';

    public function handle()
    {
        $ignore_locked = $this->option('ignore_locked');
        $ignore_no_videos = $this->option('ignore_no_videos');
        $site_id = $this->option('site_id');
        $category_id = $this->option('category_id');

        if ($ignore_locked !== 'false') {
            $ignore_locked = true;
        } else {
            $ignore_locked = false;
        }

        if ($ignore_no_videos !== 'false') {
            $ignore_no_videos = true;
        } else {
            $ignore_no_videos = false;
        }

        if ($category_id == 'false') {
            $category_id = false;
        }

        if ($site_id !== "false") {
            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("[BotUpdateCategoriesThumbnails] site id: $site_id not exists", "error",'kernel');
                exit;
            }
            $sites = Site::where('id', $site_id)->get();
        } else {
            $sites = Site::all();
        }

        DB::transaction(function () use ($sites, $ignore_locked, $ignore_no_videos, $category_id) {
            foreach ($sites as $site) {
                if ($category_id) {
                    echo $category_id;
                    $categories = Category::where('id', '=', $category_id)
                        ->where('site_id', '=', $site->id)
                        ->get()
                    ;
                } else {
                    $categories = Category::where('site_id', '=', $site->id)->get();
                }

                rZeBotUtils::message("[BotUpdateCategoriesThumbnails] Updating thumbnails for '" . $site->getHost() . "'", "warning",'kernel');

                $scenes_id_used = [];
                foreach($categories as $category) {
                    $scene_id = Category::updateCategoryThumbnail($category, $scenes_id_used, $ignore_locked, $ignore_no_videos);
                    if ($scene_id) {
                        $scenes_id_used[] = $scene_id;
                    }
                }
            }
        });
    }
}