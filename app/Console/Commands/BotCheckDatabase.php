<?php

namespace App\Console\Commands;

use App\Model\CategoryTranslation;
use App\Model\SceneTranslation;
use App\Model\Tag;
use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use App\Model\Scene;
use App\Model\Site;
use App\Model\Language;
use App\Model\Category;

class BotCheckDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:database:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show info database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        rZeBotUtils::message("Check Database For Sexodome", "cyan", true, true);

        $totalScenes = Scene::all()->count();
        rZeBotUtils::message("Total scenes: " . $totalScenes, "white", true, true);

        $totalSites = Site::all()->count();
        rZeBotUtils::message("Total sites: " . $totalSites, "white", true, true);

        echo PHP_EOL;

        rZeBotUtils::message("Check count scenes for individual sites", "cyan", true, true);
        $sites = Site::all();

        $sumScenes = 0;
        foreach($sites as $site) {
            $siteScenesCount = $site->scenes()->count();
            $sumScenes+=$siteScenesCount;
            rZeBotUtils::message("http://".$site->getHost() . ": " . $siteScenesCount, "yellow", true, true);
        }

        if ($totalScenes == $sumScenes) {
            rZeBotUtils::message("Check individual scenes OK: " . $totalScenes . "/sum(" . $sumScenes.")", "green", true, true);
        } else {
            rZeBotUtils::message("Check individual scenes KO: " . $totalScenes . "/sum(" . $sumScenes.")", "red", true, true);
        }

        echo PHP_EOL;
        rZeBotUtils::message("Check count scenes and translations", "cyan", true, true);
        $totalTranslations = SceneTranslation::all()->count();
        $countLanguages = Language::all()->count();
        rZeBotUtils::message("Total languages: " . $countLanguages, "white", true, true);
        rZeBotUtils::message("Total translations: " . $totalTranslations, "white", true, true);

        if (($totalTranslations/$countLanguages) != $totalScenes) {
            rZeBotUtils::message("Check languages/Translations failed: $totalTranslations/$countLanguages = ". ($totalTranslations/$countLanguages), "red", true, true);
        } else {
            rZeBotUtils::message("Check invidual trasnslations success: $totalTranslations/$countLanguages = ". ($totalTranslations/$countLanguages), "green", true, true);
        }

        echo PHP_EOL;
        rZeBotUtils::message("Check individual scene translations", "cyan", true, true);
        $scenes = Scene::all();

        $errorScenes = 0;
        $successScenes = 0;
        foreach($scenes as $scene) {
            $totalSceneTranslations = $scene->translations()->count();

            if ($totalSceneTranslations == $countLanguages) {
                $color = "green";
                $successScenes++;
            } else {
                $color = "red";
                $errorScenes++;
            }
        }

        if ($errorScenes > 0) {
            rZeBotUtils::message("Check invidual trasnslations failed: success($successScenes)/error($errorScenes)", "red", true, true);
        } else {
            rZeBotUtils::message("Check invidual trasnslations success: success($successScenes)/error($errorScenes)", "green", true, true);
        }

        echo PHP_EOL;
        rZeBotUtils::message("Check categories", "cyan", true, true);
        $totalCategories = Category::all()->count();
        $totalCategoriesTranslations = CategoryTranslation::all()->count();
        rZeBotUtils::message("Total categories: $totalCategories", "white", true, true);
        rZeBotUtils::message("Total categories translations: $totalCategoriesTranslations", "white", true, true);

        if (($totalCategoriesTranslations/$countLanguages) != $totalCategories) {
            rZeBotUtils::message("Check languages/Translations failed: $totalCategoriesTranslations/$countLanguages = ". ($totalCategoriesTranslations/$countLanguages), "red", true, true);
        } else {
            rZeBotUtils::message("Check invidual trasnslations success: $totalCategoriesTranslations/$countLanguages = ". ($totalCategoriesTranslations/$countLanguages), "green", true, true);
        }

        echo PHP_EOL;
        rZeBotUtils::message("Check scene with zero categories", "cyan", true, true);
        $countSceneZeroCategories = 0;
        foreach($scenes as $scene) {
            if ($scene->categories()->count() == 0) {
                $countSceneZeroCategories++;
            }
        }
        if ($countSceneZeroCategories == 0) {
            $color = "green";
        } else {
            $color = "red";
        }
        rZeBotUtils::message("Scenes with ZeroCategories: $countSceneZeroCategories", $color, true, true);

        echo PHP_EOL;
        rZeBotUtils::message("Check tags isValid for categories", "cyan", true, true);

        $countTags = Tag::all()->count();
        rZeBotUtils::message("Total tags: $countTags", "white", true, true);

        $tags = Tag::all();
        $countNotValidTag = 0;
        $countValidTag = 0;
        foreach ($tags as $tag) {
            $tagTranslation = $tag->translations()->where('language_id', $english = 2)->first();
            $transformedTag = rZeBotUtils::transformTagForCategory($tagTranslation->name);
            if (!rZeBotUtils::isValidTag($transformedTag)) {
                $countNotValidTag++;
            } else {
                $countValidTag++;
            }
        }
        if ($countNotValidTag > 0) {
            $color = "red";
        } else {
            $color = "green";
        }
        rZeBotUtils::message("Tags not valid: yes($countValidTag)/no($countNotValidTag)", $color, true, true);
    }
}
