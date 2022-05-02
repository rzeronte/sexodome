<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use Illuminate\Support\Facades\DB;
use App\Model\Tag;

class BotScenesTaggerize extends Command
{

    protected $signature = 'zbot:scenes:taggerize {site_id}';

    protected $description = 'Auto associate tags for scenes from title (words >=2 length)';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("[BotScenesTaggerize] El site id: $site_id no existe", "error",'kernel');
            return;
        }

        $scenes = $site->scenes()->get();

        DB::transaction(function () use ($scenes, $site_id, $site) {
            foreach($scenes as $scene) {
                $scene_title = $scene->translations('language_id', 2)->first()->title;

                $palabras = explode(" ", $scene_title);

                $associated_tag_ids = [];

                foreach ($palabras as $palabra) {
                    if (strlen($palabra) >= 4) {
                        if (Tag::getTranslationSearch($palabra, 2, $site_id)->count() > 0) {
                            foreach (Tag::getTranslationSearch($palabra, 2, $site_id)->orderBy(DB::raw('LENGTH(name)'))->limit(7)->get() as $tag) {
                                $associated_tag_ids[] = $tag->id;
                            }
                        }
                    }
                }

                $associated_tag_ids = array_unique($associated_tag_ids);
                rZeBotUtils::message("[BotScenesTaggerize] Escena " . $scene->id . " asociada con " . count($associated_tag_ids) . " tags", "info",'kernel');

                $scene->tags()->sync($associated_tag_ids);
            }
        });


    }

}
