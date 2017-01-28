<?php

namespace App\Console\Commands;

use App\Model\CategoryTranslation;
use App\Model\LanguageTag;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Scene;
use App\Model\Host;
use App\Model\FixTranslation;
use App\Model\TagTranslation;
use App\Model\Tag;

class BotTranslateVideo extends Command
{

    public $apiKey;
    public $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:translate:video {from} {to} {scene_id}
                            {--categories=true : Determine if translate categories}
                            {--tags=true : Determine if translate tags}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate a video from a site (with lang codes)';

    function __construct()
    {
        parent::__construct();

        $this->apiKey = "AIzaSyBgQ5lWXiC4vqpVseZTD9fYHQkmEc5mrV4";
        $this->urlTranslation = "https://www.googleapis.com/language/translate/v2?";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from = $this->argument('from');
        $to = $this->argument('to');
        $scene_id = $this->argument('scene_id');

        $tags = $this->option('tags');
        $categories = $this->option('categories');

        $scene = Scene::find($scene_id);

        if (!$scene) {
            rZeBotUtils::message('[ERROR]: La scene : '. $scene_id. " no existe", "red", false, false);
            die();
        }

        $this->translateScene($from, $to, $scene);

        if ($tags == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateTags($from, $to, $scene);
            }
        } else {
            rZeBotUtils::message('[WARNING] Ignorando traducción de TAGS para el video: '.$scene_id, "yellow", false, false);
        }

        if ($categories == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateCategories($from, $to, $scene);
            }
        } else {
            rZeBotUtils::message('[WARNING] Ignorando traducción de CATEGORIES para el video: '.$scene_id, "yellow", false, false);
        }
    }

    public function translateScene($from, $to, $scene)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        if (!$languageFrom || !$languageTo) {
            rZeBotUtils::message('[ERROR] Languages not found. Remember use his code. (es, en, de, it, de, fr)', "red", false, false);
            exit;
        }

        $translationTo = $scene->translations()->where('language_id', $languageTo->id)->first();

        if (!$translationTo->title == null || !$translationTo->permalink == null) {
            rZeBotUtils::message('[WARNING TRANSLATION ALREADY EXISTS] scene_id(' . $scene->id . ')' .$translationTo->title, "yellow", false, false);
            return;
        }

        $textFrom = $scene->translations()->where('language_id', $languageFrom->id)->first();

        if (!$textFrom || !$translationTo) {
            rZeBotUtils::message('[ERROR] Translations not found for scene_id:' . $scene->id, "red", false, false);
            return;
        }

        $translationTitle = $this->translateText($textFrom->title, $from, $to);
        $translationDescription = $this->translateText($textFrom->description, $from, $to);

        $translation = true;
        if ($translationTitle != false) {
            $translationTo->title = $translationTitle;
            $translationTo->permalink = str_slug($translationTitle);
            rZeBotUtils::message("[TRANSLATING TITLE] scene_id: $scene->id, '". $textFrom->title . "' => '$translationTo->title'", "cyan", false, false);
        } else {
            $translation = false;
            rZeBotUtils::message('[ERROR TRANSLATING TITLE] scene_id: '. $scene->id. ', '. $textFrom->title . '" => "'.$translationTo->title.'"', "red", false, false);
        }

        if (strlen($textFrom->description) > 0){
            if ($translationDescription != false) {
                $translationTo->description = $translationDescription;
                rZeBotUtils::message("[TRANSLATING DESCRIPTION] scene_id: $scene->id, '". $textFrom->title . "' => '$translationTo->title'", "cyan", false, false);
            } else {
                $translation = false;
                rZeBotUtils::message('[ERROR TRANSLATING DESCRIPTION] scene_id: '. $scene->id. ', '. $translationDescription, "red", false, false);
            }
        }

        $translationTo->save();

        if  (!$translation) {
            rZeBotUtils::message('[ERROR TRANSLATING DELETING] scene_id: '. $scene->id, "red", false, false);
            $scene->delete();
        }

    }

    public function translateTags($from, $to, $scene)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        if (!$languageFrom || !$languageTo) {
            rZeBotUtils::message('[ERROR] Languages not found. Remember use his code. (es, en, de, it, de, fr)', "red", false, false);
            exit;
        }

        foreach ($scene->tags()->get() as $tag) {

            $translationTo = $tag->translations()->where('language_id', $languageTo->id)->first();

            if (!$translationTo->title == null || !$translationTo->permalink == null) {
                rZeBotUtils::message('[WARNING TRANSLATION ALREADY EXISTS] tag_id(' . $tag->id . ') ' . $translationTo->title, "yellow", false, false);
                continue;
            }

            $textFrom = $tag->translations()->where('language_id', $languageFrom->id)->first();

            if (!$textFrom || !$translationTo) {
                rZeBotUtils::message('[ERROR TRANSLATION TAG] Cant get from and to objects', "red", false, false);
                continue;
            }

            $translationName = $this->translateText($textFrom->name, $from, $to);

            if ($translationName !== false) {

                $fixTranslation = $this->checkForFixTranslation($translationName, $to, $scene->site->user_id);

                if ($fixTranslation !== false) {
                    // Buscamos si ya existe la categoría del fix
                    $alreadyTagTranslation = TagTranslation::select('tag_translations.*')
                        ->join('tags', 'tags.id', '=', 'tag_translations.tag_id')
                        ->where('tags.site_id', '=', $tag->site_id)
                        ->where("tag_translations.language_id", $languageTo->id)
                        ->where("tag_translations.name", "like", $fixTranslation->to)
                        ->first()
                    ;

                    if (!$alreadyTagTranslation) {
                        $translationTo->name = $fixTranslation->to;
                        $translationTo->permalink = str_slug($fixTranslation->to);
                        $translationTo->save();
                        rZeBotUtils::message('[TRANSLATION TAG WITH FIX TRANSLATION] ' . $textFrom->name . " - " . $fixTranslation->to, "yellow", false, false);
                    } else {
                        rZeBotUtils::message('[TRANSLATION TAG WITH FIX TRANSLATION, BUT ALREADY EXISTS, MIXING] TranslationId: '. $translationTo->id . " " . $translationName . " - " . $fixTranslation->to, "yellow", false, false);
                        $ids_sync = $tag->scenes()->select('scenes.id')->get()->pluck('id');
                        $ids_sync = array_unique($ids_sync->all());

                        $currentCategoryScenes = $alreadyTagTranslation->tag->scenes()->select('scenes.id')->get()->pluck('id');
                        $currentCategoryScenes = array_unique($currentCategoryScenes->all());

                        $totalIds = array_unique(array_merge($ids_sync, $currentCategoryScenes));

                        $alreadyTagTranslation->tag->scenes()->sync($totalIds);
                        //$tag->status = 2; // El actual queda descartada, nos quedamos con la que existía
                        //$tag->save();

                    }

                } else {
                    $translationTo->name = $translationName;
                    $translationTo->permalink = str_slug($translationName);
                    $translationTo->save();
                    rZeBotUtils::message('[TRANSLATION TAG] ' . $textFrom->name . " - " . $translationName, "green", false, false);
                }

            } else {
                rZeBotUtils::message('[ERROR API TRANSLATION] tag_id: ' . $tag->id, "red", false, false);
            }

        }
    }

    public function translateCategories($from, $to, $scene)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        if (!$languageFrom || !$languageTo) {
            rZeBotUtils::message('[ERROR] Languages not found. Remember use his code. (es, en, de, it, de, fr)', "red", false, false);
            exit;
        }

        foreach ($scene->categories()->get() as $category) {

            $translationTo = $category->translations()->where('language_id', $languageTo->id)->first();

            // Evitamos traducir de nuevo si ya existe traducción
            if (!$translationTo->title == null || !$translationTo->permalink == null) {
                rZeBotUtils::message('[WARNING TRANSLATION ALREADY EXISTS] category_id(' .$category->id.')' . $translationTo->title, "yellow", false, false);
                continue;
            }

            $textFrom = $category->translations()->where('language_id', $languageFrom->id)->first();

            if (!$textFrom || !$translationTo) {
                rZeBotUtils::message('[ERROR TRANSLATION CATEGORY] Cant get from and to objects', "red", false, false);
                continue;
            }

            $translationName = $this->translateText($textFrom->name, $from, $to);

            if ($translationName !== false) {

                $fixTranslation = $this->checkForFixTranslation($translationName, $to, $scene->site->user_id);

                if ($fixTranslation !== false) {

                    // Buscamos si ya existe la categoría del fix
                    $alreadyCategoryTranslation = CategoryTranslation::select('categories_translations.*')
                        ->join('categories', 'categories.id', '=', 'categories_translations.category_id')
                        ->where('categories.site_id', '=', $category->site_id)
                        ->where("categories_translations.language_id", $languageTo->id)
                        ->where("categories_translations.name", "like", $fixTranslation->to)
                        ->first()
                    ;

                    if (!$alreadyCategoryTranslation) {
                        $translationTo->name = $fixTranslation->to;
                        $translationTo->permalink = str_slug($fixTranslation->to);
                        $translationTo->save();
                        rZeBotUtils::message('[TRANSLATION CATEGORY WITH FIX TRANSLATION] TranslationId: '. $translationTo->id . " " . $textFrom->name . " - " . $fixTranslation->to, "yellow", false, false);
                    } else {
                        rZeBotUtils::message('[TRANSLATION CATEGORY WITH FIX TRANSLATION, BUT ALREADY EXISTS, MIXING] TranslationId: '. $translationTo->id . "-".$translationName . " - " . $fixTranslation->to, "yellow", false, false);
                        $ids_sync = $category->scenes()->select('scenes.id')->get()->pluck('id');
                        $ids_sync = array_unique($ids_sync->all());
                        $currentCategoryScenes = $alreadyCategoryTranslation->category->scenes()->select('scenes.id')->get()->pluck('id');
                        $currentCategoryScenes = $currentCategoryScenes->all();

                        $totalIds = array_unique(array_merge($ids_sync, $currentCategoryScenes));

                        $alreadyCategoryTranslation->category->scenes()->sync($totalIds);
                        //$category->status = 0; // La actual queda descartada, nos quedamos con la que existía
                        //$category->save();
                    }

                } else {
                    $translationTo->name = $translationName;
                    $translationTo->permalink = str_slug($translationName);
                    $translationTo->save();
                    rZeBotUtils::message('[TRANSLATION CATEGORY] ' . $textFrom->name . " - " . $translationName, "green", false, false);
                }

            } else {
                rZeBotUtils::message('[ERROR API TRANSLATION] category_id: ' . $category->id, "red", false, false);
            }
        }
    }

    public function translateText($text, $from, $to)
    {
        $data = array(
            "key"    => $this->apiKey,
            "q"      => rawurldecode($text),
            "source" => $from,
            "target" => $to
        );

        $finalUrl = $this->urlTranslation.\GuzzleHttp\Psr7\build_query($data);

        $handle = curl_init($finalUrl);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        $responseDecoded = json_decode($response, true);
        curl_close($handle);

        if (isset($responseDecoded['error'])) {
            $translated = false;
        } else {
            $translated = $responseDecoded['data']['translations'][0]['translatedText'];
        }

        return $translated;
    }

    public function checkForFixTranslation($text, $lang_to, $user_id)
    {
        $languageTo = Language::where('code', $lang_to)->first();

        $fixTranslations = FixTranslation::where('user_id', $user_id)
            ->where('language_id', $languageTo->id)
            ->where('from', '=', $text)
            ->first()
        ;

        if (!$fixTranslations)
        {
            return false;
        }

        return $fixTranslations;

    }

}