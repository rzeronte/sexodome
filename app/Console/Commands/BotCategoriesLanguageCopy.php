<?php

namespace App\Console\Commands;

use App\Model\CategoryTranslation;
use App\Model\Language;
use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;

class BotCategoriesLanguageCopy extends Command
{
    protected $signature = 'zbot:categories:copy {site_id} {code_from} {code_to}';

    protected $description = 'Copy categories language to a new language';

    public function handle()
    {
        $site_id   = $this->argument('site_id');
        $code_from = $this->argument('code_from');
        $code_to   = $this->argument('code_to');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");
            exit;
        }

        $languageFrom = Language::where('code', $code_from)->first();
        $languageTo = Language::where('code', $code_to)->first();

        if (!$languageTo || !$languageFrom) {
            rZeBotUtils::message("Hay algún problema para cargar los idiomas '$code_from' y/o '$code_to'", "red");
            exit;
        }

        if (!$this->confirm("Quieres vaciar el idioma '$code_to' de las categorías?")) {
            DB::table('categories_translations')
                ->where('site_id', $site->id)
                ->where('language_id', $languageTo->id)
                ->delete()
            ;
        }

        $categories = Category::where('site_id', '=', $site->id)->get();

        rZeBotUtils::message("Copy language '$code_from' to '$code_to' in " . $site->getHost(), "cyan", false, false);

        foreach($categories as $category) {

            $translation = $category->translations()->where('language_id', $languageFrom->id)->first();
            if (!$translation) {
                rZeBotUtils::message("[ERROR CategoryLanguageCopy] $category->id no tiene traducción desde '$code_from'", "red");
                exit;
            }

            rZeBotUtils::message("[SUCCESS] $category->id  '$code_from' -> '$code_to'", "green");

            $newTranslation = new CategoryTranslation();
            $newTranslation->category_id = $category->id;
            $newTranslation->language_id = $languageTo->id;
            $newTranslation->name = $translation->name;
            $newTranslation->permalink = $translation->permalink;
            $newTranslation->thumb = $translation->thumb;
            $newTranslation->thumb_locked = $translation->thumb_locked;
            $newTranslation->save();
        }
    }
}