<?php

namespace App\Console\Commands;

use App\Model\CategoryTranslation;
use App\Model\SceneTranslation;
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
        $totalScenes = Scene::all()->count();
        rZeBotUtils::message("Total scenes: " . $totalScenes, "green");

        $totalSites = Site::all()->count();
        rZeBotUtils::message("Total sites: " . $totalSites, "green");

        echo PHP_EOL;

        rZeBotUtils::message("Check count scenes for individual sites", "cyan");
        $sites = Site::all();

        $sumScenes = 0;
        foreach($sites as $site) {
            $siteScenesCount = $site->scenes()->count();
            $sumScenes+=$siteScenesCount;
            rZeBotUtils::message($site->getHost() . ": " . $siteScenesCount, "yellow");
        }

        if ($totalScenes == $sumScenes) {
            rZeBotUtils::message("Check individual scenes OK: " . $totalScenes . "/sum(" . $sumScenes.")", "green");
        } else {
            rZeBotUtils::message("Check individual scenes KO: " . $totalScenes . "/sum(" . $sumScenes.")", "red");
        }

        echo PHP_EOL;
        rZeBotUtils::message("Check count scenes and translations", "cyann");
        $totalTranslations = SceneTranslation::all()->count();
        $countLanguages = Language::all()->count();
        rZeBotUtils::message("Total languages: " . $countLanguages, "green");
        rZeBotUtils::message("Total translations: " . $totalTranslations, "green");

        if (($totalTranslations/$countLanguages) != $totalScenes) {
            rZeBotUtils::message("Check languages/Translations failed: $totalTranslations/$countLanguages = ". ($totalTranslations/$countLanguages), "red");
        } else {
            rZeBotUtils::message("Check invidual trasnslations success: $totalTranslations/$countLanguages = ". ($totalTranslations/$countLanguages), "green");
        }

        echo PHP_EOL;
        rZeBotUtils::message("Check individual scene translations", "cyan");
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
            rZeBotUtils::message("Check invidual trasnslations failed: success($successScenes)/error($errorScenes)", "red");
        } else {
            rZeBotUtils::message("Check invidual trasnslations success: success($successScenes)/error($errorScenes)", "green");
        }

        echo PHP_EOL;
        rZeBotUtils::message("Check categories", "green");
        $totalCategories = Category::all()->count();
        $totalCategoriesTranslations = CategoryTranslation::all()->count();
        rZeBotUtils::message("Total categories: $totalCategories", "green");
        rZeBotUtils::message("Total categories translations: $totalCategoriesTranslations", "green");

        if (($totalCategoriesTranslations/$countLanguages) != $totalCategories) {
            rZeBotUtils::message("Check languages/Translations failed: $totalCategoriesTranslations/$countLanguages = ". ($totalCategoriesTranslations/$countLanguages), "red");
        } else {
            rZeBotUtils::message("Check invidual trasnslations success: $totalCategoriesTranslations/$countLanguages = ". ($totalCategoriesTranslations/$countLanguages), "green");
        }

    }
}
