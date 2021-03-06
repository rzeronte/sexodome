<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Scene;
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
            return;
        }

        // Obtenemos los tags del video
        $video_tags = $scene->tags()->select('tags.id')->get()->pluck('id');
        $video_tags_ids = $video_tags->all();

        $categories = Category::getCategoriesFromTagsArray($scene->site->id, $video_tags_ids)->get()->pluck('id');
        $categories_ids = $categories->all();
        $scene->categories()->sync($categories_ids);

        rZeBotUtils::message("[BotCategorizeVideo] Categorizing scene_id: $scene_id with " . count($categories_ids)  . " categories", "info", 'kernel');

        return;
    }
}