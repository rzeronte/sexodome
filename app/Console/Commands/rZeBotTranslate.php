<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\SceneClick;
use App\Model\SceneTranslation;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Scene;
use App\Model\Tag;
use App\Model\Host;
use App\Model\SceneTag;
use App\Model\TagClick;
use App\Model\Site;

class rZeBotTranslate extends Command
{

    public $apiKey;
    public $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:translate {from} {to} {site_id}
                            {--scenes=true : Determine if translate scenes}
                            {--categories=true : Determine if translate categories}
                            {--tags=true : Determine if translate tags}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch rZeBot for translations with Google API';

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
        $site_id = $this->argument('site_id');

        $scenes = $this->option('scenes');
        $tags = $this->option('tags');
        $categories = $this->option('categories');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message('[ERROR]: El site_id: '. $site_id . " no existe", "red");
            die();
        }

        if ($scenes == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateScenes($from, $to, $site_id);
            }
        } else {
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

            $translationTitle = $this->translateText($textFrom->title, $from, $to);

            if ($translationTitle != false) {
                $translationTo->title = $translationTitle;
                $translationTo->permalink = str_slug($translationTitle);
                $translationTo->save();
                echo "[CATEGORY] " . $textFrom->title . " - " . $translationTitle.PHP_EOL;
            } else {
                echo "[ERROR CATEGORY TRANSLATION] $from <-> $to" . $textFrom->name . " - " . $translationTitle .PHP_EOL;
            }
        }

    }

    public function translateScenes($from, $to, $site_id)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        $scenes = Scene::where('site_id', $site_id)->get();
        $i=0;
        foreach ($scenes as $scene) {
            echo "[ " . number_format(($i*100)/ count($scenes), 0) ."% ]";
            $i++;

            $textFrom = $scene->translations()->where('language_id', $languageFrom->id)->first();
            $translationTo = $scene->translations()->where('language_id', $languageTo->id)->first();

            if (!$textFrom || !$translationTo) {
                echo "[ERROR SCENE] Can't get from and to objects".PHP_EOL;
                continue;
            }

            echo " | Current From: " . $textFrom->title . " ";
            echo " | Current To: " . $translationTo->title . " ";
            $translationTitle = $this->translateText($textFrom->title, $from, $to);

            if ($translationTitle != false) {
                $translationTo->title = $translationTitle;
                $translationTo->permalink = str_slug($translationTitle);
                $translationTo->save();
                echo "[SCENE] " . $textFrom->title . " - " . $translationTitle.PHP_EOL;
            } else {
                echo "[ERROR SCENES TRANSLATION] $from <-> $to " . $textFrom->name . " - " . $translationTitle .PHP_EOL;
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
}
