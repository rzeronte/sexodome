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

class rZeBotTranslate extends Command
{

    public $apiKey;
    public $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:translate {from} {to}
                            {--scenes=true : Default options translate scenes}
                            {--tags=true : Default options translate tags}';

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

        $scenes = $this->option('scenes');
        $tags = $this->option('tags');

        if ($scenes == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateScenes($from, $to);
            }
        } else {
            echo "[SCENES] jump scenes".PHP_EOL;
        }

        if ($tags == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateTags($from, $to);
            }
        } else {
            echo "[SCENES] jump tags".PHP_EOL;
        }
    }

    public function translateScenes($from, $to)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        $scenes = Scene::all();
        $i=0;
        foreach ($scenes as $scene) {
            echo "[ " . number_format(($i*100)/ count($scenes), 0) ."% ]";
            $i++;

            $textFrom = $scene->translations()->where('language_id', $languageFrom->id)->first();
            $translationTo = $scene->translations()->where('language_id', $languageTo->id)->first();

            $translationTitle = $this->translateText($textFrom->title, $from, $to);

            $translationTo->title = $translationTitle;
            $translationTo->permalink = str_slug($translationTitle);
            $translationTo->save();
            echo "[SCENE] " . $textFrom->title . " - " . $translationTitle.PHP_EOL;

        }

    }

    public function translateTags($from, $to)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        $tags = Tag::all();
        $i=0;
        foreach ($tags as $tag) {
            echo "[ " . number_format(($i*100)/ count($tags), 0) ."% ]";
            $i++;

            $textFrom = $tag->translations()->where('language_id', $languageFrom->id)->first();
            $translationTo = $tag->translations()->where('language_id', $languageTo->id)->first();

            $translationName = $this->translateText($textFrom->name, $from, $to);

            $translationTo->name = $translationName;
            $translationTo->permalink = str_slug($translationName);
            $translationTo->save();

            echo "[TAG] " . $textFrom->name . " - " . $translationName.PHP_EOL;
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

        $translated = $responseDecoded['data']['translations'][0]['translatedText'];

        return $translated;
    }
}
