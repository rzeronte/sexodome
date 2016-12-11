<?php

namespace App\Console\Commands;

use App\Model\Language;
use App\Model\LanguageTag;
use App\Model\SceneCategory;
use App\Model\ScenePornstar;
use App\Model\Site;
use App\Model\Tag;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Category;
use DB;

class BotSiteCopy extends Command
{
    protected $signature = 'zbot:sites:copy {origin_site_id}';

    protected $description = 'List sites information';

    public function handle()
    {
        $origin_site_id = $this->argument("origin_site_id");
        $site_from = Site::find($origin_site_id);
        $site_from->load("scenes", "tags", "pornstars", "categories");

        if (!$site_from) {
            rZeBotUtils::message("site_id: $origin_site_id not found", "red", true, true);
            return;
        }

        if (!$this->confirm('Are you sure for duplicate ('.$site_from->id.':'.$site_from->domain.')')) {
            return;
        }

        $domain = $this->ask("What is the new domain?");
        $newSite = $this->createSite($domain, $site_from);

        $scenes_to_copy = $site_from->scenes()->get();
        DB::transaction(function () use ($scenes_to_copy, $newSite, $site_from) {
            foreach($scenes_to_copy as $stc) {
                // copy scene
                $newScene = $this->copyScene($stc, $newSite);

                // copy categories
                $this->copyCategories($stc, $newScene, $newSite);

                // copy tags
                $this->copyTags($stc, $newScene, $newSite, $site_from);

                // copy pornstars
                $this->copyPornstars($stc, $newScene, $newSite);
            }
        });
    }

    public function createSite($domain, $site_from)
    {
        $newSite = $site_from->replicate();

        $newSite->name = "Copy of " . $site_from->domain;
        $newSite->domain = $domain;
        $newSite->banner_script1 = null;
        $newSite->banner_script2 = null;
        $newSite->banner_script3 = null;
        $newSite->banner_mobile1 = null;
        $newSite->banner_video1 = null;
        $newSite->banner_video2 = null;
        $newSite->google_analytics = null;
        $newSite->ga_account = null;

        $newSite->push();

        return $newSite;
    }

    public function copyScene($scene, $site_to)
    {
        rZeBotUtils::message("[COPY SCENE] $scene->id", "green", true, true);

        $newScene = $scene->replicate();
        $newScene->site_id = $site_to->id;
        $newScene->save();

        // scene translations
        foreach ($scene->translations()->get() as $trans) {
            $newTrans = $trans->replicate();
            $newTrans->scene_id = $newScene->id;
            $newTrans->save();
        }
        return $newScene;
    }

    public function copyCategories($scene_from, $scene_to, $site_to)
    {
        $categories_to_copy = $scene_from->categories()->get();
        foreach($categories_to_copy as $ctc) {
            $cat_in_newsite = Category::where('text', $ctc->text)->where('site_id', $site_to->id)->first();

            if (!$cat_in_newsite) {
                $newCat = $ctc->replicate();
                $newCat->site_id = $site_to->id;
                $newCat->save();

                // categories translations
                foreach ($ctc->translations()->get() as $trans) {
                    $newTrans = $trans->replicate();
                    $newTrans->category_id = $newCat->id;
                    $newTrans->save();
                }
            } else {
                $newCat = $cat_in_newsite;
            }

            // Scene-Category relationship
            try {
                $newSceneCat = new SceneCategory();
                $newSceneCat->scene_id = $scene_to->id;
                $newSceneCat->category_id = $newCat->id;
                $newSceneCat->save();
            } catch(\Exception $e) {
                rZeBotUtils::message("[CATEGORY ALREADY FOR SCENE] $ctc->text($cat_in_newsite->id)", "red", false, false);
            }
        }
    }

    public function copyTags($scene_from, $scene_to, $site_to, $site_from)
    {
        $tags_to_copy = $scene_from->tags()->get();
        $language_origin = Language::where('id', $site_from->language_id)->first();

        foreach($tags_to_copy as $ttc) {
            $tagTranslation = $ttc->translations()->where('language_id', $language_origin->id)->first();

            $tag_in_newsite = TagTranslation::join('tags', 'tags.id', '=', 'tag_translations.tag_id')
                ->where('name', $tagTranslation->name)
                ->where('tags.site_id', $site_to->id)
                ->first()
            ;

            if (!$tag_in_newsite) {
                $newTag = $ttc->replicate();
                $newTag->site_id = $site_to->id;
                $newTag->save();

                // categories translations
                foreach ($ttc->translations()->get() as $trans) {
                    $newTrans = $trans->replicate();
                    $newTrans->tag_id = $newTag->id;
                    $newTrans->save();
                }
            } else {
                $newTag = Tag::find($tag_in_newsite->tag_id);
            }

            // Scene-Tagrelationship
            try {
                $newSceneTag = new SceneCategory();
                $newSceneTag->scene_id = $scene_to->id;
                $newSceneTag->tag_id = $newTag->id;
                $newSceneTag->save();
            } catch(\Exception $e) {
                rZeBotUtils::message("[TAG ALREADY FOR SCENE] $ttc->id", "red", false, false);
            }
        }
    }

    public function copyPornstars($scene_from, $scene_to, $site_to)
    {
        $pornstars_to_copy = $scene_from->pornstars()->get();
        foreach($pornstars_to_copy as $ptc) {
            $pornstar_in_newsite = Pornstar::where('name', $ptc->name)->where('site_id', $site_to->id)->first();

            if (!$pornstar_in_newsite) {
                $newPornstar = $ptc->replicate();
                $newPornstar->site_id = $site_to->id;
                $newPornstar->save();
            } else {
                $newPornstar= $pornstar_in_newsite;
            }

            // Scene-Pornstar relationship
            try {
                $newScenePornstar = new ScenePornstar();
                $newScenePornstar->scene_id = $scene_to->id;
                $newScenePornstar->pornstar_id = $newPornstar->id;
                $newScenePornstar->save();
            } catch(\Exception $e) {
                rZeBotUtils::message("[PORNSTAR ALREADY FOR SCENE] $ptc->text($pornstar_in_newsite->id)", "red", false, false);
            }
        }
    }

}