<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Scene;
use App\Model\Tag;
use App\Model\Site;
use App\Model\Category;

class BotTranslateLanguage extends Command
{

    public $apiKey;
    public $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:translate:language {from} {to} {site_id}
                            {--scenes=true : Determine if translate scenes}
                            {--categories=true : Determine if translate categories}
                            {--tags=true : Determine if translate tags}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate a full language for a site with Google Translation';

    function __construct()
    {
        parent::__construct();

        $this->apiKey = env('GOOGLE_TRANSLATE_APIKEY');
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
        $site_id = $this->argument('site_id');

        $scenes = $this->option('scenes');
        $tags = $this->option('tags');
        $categories = $this->option('categories');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message('[BotTranslateLanguage] El site_id: '. $site_id . " no existe", "error",'kernel');
            die();
        }

        if ($scenes == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateScenes($from, $to, $site_id);
            }
        } else {
            rZeBotUtils::message("[BotTranslateLanguage] jump scenes", "error",'kernel');
            echo "[SCENES] jump scenes".PHP_EOL;
        }

        if ($tags == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateTags($from, $to, $site_id);
            }
        } else {
            echo "[SCENES] jump tags".PHP_EOL;
        }

        if ($categories == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateCategories($from, $to, $site_id);
            }
        } else {
            echo "[SCENES] jump categories".PHP_EOL;
        }

    }

    public function translateScenes($from, $to, $site_id)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        if (!$languageFrom || !$languageTo) {
            rZeBotUtils::message("[BotTranslateLanguage] Languages not found. Remember use his code. (es, de, it, en, de, br)", "error",'kernel');
            exit;
        }

        $scenes = Scene::where('site_id', $site_id)->get();
        $i=0;
        foreach ($scenes as $scene) {
            rZeBotUtils::message("[BotTranslateLanguage] ( " . number_format(($i*100)/ count($scenes), 0) ."% )", "info",'kernel');

            $i++;

            $textFrom = $scene->translations()->where('language_id', $languageFrom->id)->first();
            $translationTo = $scene->translations()->where('language_id', $languageTo->id)->first();

            if (!$textFrom || !$translationTo) {
                rZeBotUtils::message("[BotTranslateLanguage] Error scene: Can't get 'from' and 'to' objects", "info",'kernel');
                continue;
            }

            rZeBotUtils::message("[BotTranslateLanguage] Current From: " . $textFrom->title . " ", "info",'kernel');
            rZeBotUtils::message("[BotTranslateLanguage] Current To: " . $translationTo->title . " ", "info",'kernel');

            $translationTitle = $this->translateText($textFrom->title, $from, $to);

            if ($translationTitle != false) {
                $translationTo->title = $translationTitle;
                $translationTo->permalink = str_slug($translationTitle);
                $translationTo->save();
                rZeBotUtils::message("[BotTranslateLanguage] Translating '" . $textFrom->title . "' - '" . $translationTitle. "'", "info", 'kernel');
            } else {
                rZeBotUtils::message("[BotTranslateLanguage] $from <-> $to '" . $textFrom->name . "' - '" . $translationTitle . "'", "info",'kernel');
            }
        }

    }

    public function translateTags($from, $to, $site_id)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        $tags = Tag::where('site_id', $site_id)->get();

        $i=0;
        foreach ($tags as $tag) {
            echo "[ " . number_format(($i*100)/ count($tags), 0) ."% ]";
            $i++;

            $textFrom = $tag->translations()->where('language_id', $languageFrom->id)->first();
            $translationTo = $tag->translations()->where('language_id', $languageTo->id)->first();

            if (!$textFrom || !$translationTo) {
                echo "[ERROR TAG] Can't get from and to objects".PHP_EOL;
                continue;
            }

            $translationName = $this->translateText($textFrom->name, $from, $to);

            if ($translationName != false) {
                $translationTo->name = $translationName;
                $translationTo->permalink = str_slug($translationName);
                $translationTo->save();
                echo "[TAG] " . $textFrom->name . " - " . $translationName.PHP_EOL;
            } else {
                echo "[ERROR TAG TRANSLATION] " . $textFrom->name . " - " . $translationName.PHP_EOL;
            }

        }
    }

    public function translateCategories($from, $to, $site_id) {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        $categories = Category::where('site_id', $site_id)->get();
        $i=0;
        foreach ($categories as $category) {
            echo "[ " . number_format(($i*100)/ count($categories), 0) ."% ]";
            $i++;

            $textFrom = $category->translations()->where('language_id', $languageFrom->id)->first();
            $translationTo = $category->translations()->where('language_id', $languageTo->id)->first();

            if (!$textFrom || !$translationTo) {
                echo "[ERROR CATEGORY] Can't get from and to objects".PHP_EOL;
                continue;
            }

            $translationTitle = $this->translateText($textFrom->name, $from, $to);

            if ($translationTitle != false) {
                $translationTo->name= $translationTitle;
                $translationTo->permalink = str_slug($translationTitle);
                $translationTo->save();
                echo "[CATEGORY] " . $textFrom->name. " - " . $translationTitle.PHP_EOL;
            } else {
                echo "[ERROR CATEGORY TRANSLATION] $from <-> $to" . $textFrom->name . " - " . $translationTitle .PHP_EOL;
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

        $finalUrl = $this->urlTranslation.http_build_query($data);

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
}
