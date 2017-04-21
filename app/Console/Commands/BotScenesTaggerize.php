<?php

namespace App\Console\Commands;

use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;
use Log;
use Artisan;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use DB;

class BotScenesTaggerize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:scenes:taggerize {site_id}';

    /**a
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto associate tags for scenes from title (words >=2 length)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");
            return;
        }

        $scenes = $site->scenes()->get();
        foreach($scenes as $scene) {
            $scene_title = $scene->translations('language_id', 2)->first()->title;

            $palabras = explode(" ", $scene_title);

            $associated_tag_ids = [];

            foreach ($palabras as $palabra) {
                if (strlen($palabra) >= 2) {
                    if (\App\Model\Tag::getTranslationSearch($palabra, 2, $site_id)->count() > 0) {
                        foreach (\App\Model\Tag::getTranslationSearch($palabra, 2, $site_id)->orderBy(DB::raw('LENGTH(name)'))->limit(7)->get() as $tag) {
                            $associated_tag_ids[] = $tag->id;
                        }
                    }
                }
            }

            $associated_tag_ids = array_unique($associated_tag_ids);
            rZeBotUtils::message("Escena " . $scene->id . " asociada con " . count($associated_tag_ids) . " tags", "cyan", false, false);

            $scene->tags()->sync($associated_tag_ids);
        }


    }

}
