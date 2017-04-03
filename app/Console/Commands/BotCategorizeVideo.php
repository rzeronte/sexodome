<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Scene;
use DB;
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;
use App\Model\CategoryTranslation;
use App\Model\Category;

class BotCategorizeVideo extends Command
{
    protected $signature = 'zbot:categorize:scene {scene_id}';

    protected $description = '(Re)Categorize one scene given';

    public function handle()
    {
        $scene_id = $this->argument('scene_id');

        $scene = Scene::find($scene_id);

        if (!$scene) {
            $this->error("Scene: $scene_id not found");
        }

        // Obtenemos los tags del video
        $video_tags = $scene->tags()->get()->pluck('id');
        $video_tags_ids = $video_tags->all();

        $categories = Category::where('site_id', $scene->site->id)->get();

        $scene_categories_ids = [];
        foreach($categories as $category) {
            $category_tags = $category->tags()->get()->pluck('id');
            $category_tags_ids = $category_tags->all();

            if (count(array_intersect($category_tags_ids, $video_tags_ids)) > 0) {
                $scene_categories_ids[] = $category->id;
            }
        }

        if (count($scene_categories_ids) > 0) {
            $scene->categories()->sync($scene_categories_ids);
        }

        rZeBotUtils::message("Categorizando scene_id: $scene_id con " . count($scene_categories_ids)  . " categorías", "green", false, false);

    }
}