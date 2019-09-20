<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Scene;

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
        $scene_id = $this->argument('scene_id');

        $tags = $this->option('tags');
        $categories = $this->option('categories');

        $scene = Scene::find($scene_id);

        if (!$scene) {
            rZeBotUtils::message('[BotTranslateVideo] site_id '. $scene_id. " not exists", "error",'kernel');
            die();
        }

        $this->translateScene($from, $to, $scene);

        if ($tags == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateTags($from, $to, $scene);
            }
        } else {
            rZeBotUtils::message('[BotTranslateVideo] Ignoring translations tags for scene_id: '.$scene_id, "warning",'kernel');
        }

        if ($categories == 'true') {
            if ($to != "en") {  // skip override english translations...
                $this->translateCategories($from, $to, $scene);
            }
        } else {
            rZeBotUtils::message('[BotTranslateVideo] Ignoring translations categories for scene_id: '.$scene_id, "warning",'kernel');
        }
    }

    public function translateScene($from, $to, $scene)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        if (!$languageFrom || !$languageTo) {
            rZeBotUtils::message('[BotTranslateVideo] Languages not found. Remember use his code. (es, en, de, it, de, fr)', "error",'kernel');
            exit;
        }

        $translationTo = $scene->translations()->where('language_id', $languageTo->id)->first();

        if (!$translationTo->title == null || !$translationTo->permalink == null) {
            rZeBotUtils::message("[BotTranslateVideo] Translation already exists for '". $languageTo->code. "' scene_id(" . $scene->id . ")" . $translationTo->title, "warning",'kernel');
            return;
        }

        $textFrom = $scene->translations()->where('language_id', $languageFrom->id)->first();

        if (!$textFrom || !$translationTo) {
            rZeBotUtils::message('[BotTranslateVideo] Translations not found for scene_id:' . $scene->id, "error",'kernel');
            return;
        }

        $translationTitle = $this->translateText($textFrom->title, $from, $to);
        $translationDescription = $this->translateText($textFrom->description, $from, $to);

        $translation = true;
        if ($translationTitle != false) {
            $translationTo->title = substr($translationTitle, 0, 255);
            $translationTo->permalink = str_slug($translationTitle);
            rZeBotUtils::message("[BotTranslateVideo] Translating title ($from => $to) for scene_id: $scene->id, '". $textFrom->title . "' => '$translationTo->title'", "info",'kernel');
        } else {
            $translation = false;
            rZeBotUtils::message("[BotTranslateVideo] Translating title ($from => $to) for scene_id: ". $scene->id. ', '. $textFrom->title . '" => "'.$translationTo->title.'"', "error",'kernel');
        }

        if (strlen($textFrom->description) > 0){
            if ($translationDescription != false) {
                $translationTo->description = substr(utf8_encode($translationDescription), 0, 255);
                rZeBotUtils::message("[BotTranslateVideo] Translating description ($from => $to) for scene_id: $scene->id, '". $textFrom->title . "' => '$translationTo->title'", "info",'kernel');
            } else {
                $translation = false;
                rZeBotUtils::message("[BotTranslateVideo] Translation description ($from => $to) for scene_id: ". $scene->id. ', '. $translationDescription, "error",'kernel');
            }
        }

        try {
            $translationTo->save();
        } catch (\Exception $e) {
            $translation = false;
            rZeBotUtils::message('[BotTranslateVideo] Saving translation for scene_id: '. $scene->id, "error",'kernel');
        }

        if  (!$translation) {
            rZeBotUtils::message('[BotTranslateVideo] Cant translate scene_id: '. $scene->id . ". Deleting...", "error",'kernel');
            $scene->delete();
        }

    }

    public function translateTags($from, $to, $scene)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        if (!$languageFrom || !$languageTo) {
            rZeBotUtils::message('[BotTranslateVideo] Languages not found. Remember use his code. (es, en, de, it, de, fr)', "error",'kernel');
            exit;
        }

        foreach ($scene->tags()->get() as $tag) {

            $translationTo = $tag->translations()->where('language_id', $languageTo->id)->first();

            if (!$translationTo) {
                continue;
            }

            if (!$translationTo->title == null || !$translationTo->permalink == null) {
                //rZeBotUtils::message('[BotTranslateVideo] Translation already exists for tag_id(' . $tag->id . ') ' . $translationTo->title, "warning",'kernel');
                continue;
            }

            $textFrom = $tag->translations()->where('language_id', $languageFrom->id)->first();

            if (!$textFrom || !$translationTo) {
                rZeBotUtils::message("[BotTranslateVideo] Translating tag. Cant get 'from' and 'to' objects", "error",'kernel');
                continue;
            }

            $translationName = $this->translateText($textFrom->name, $from, $to);

            if ($translationName != false) {
                $translationTo->name = $translationName;
                $translationTo->permalink = str_slug($translationName);
                $translationTo->save();
                rZeBotUtils::message("[BotTranslateVideo] Translating tag ($from => $to) | " . $textFrom->name . " - " . $translationName, "info",'kernel');
            } else {
                rZeBotUtils::message('[BotTranslateVideo] Api translation failed for tag_id: ' . $tag->id, "error",'kernel');
            }
        }
    }

    public function translateCategories($from, $to, $scene)
    {
        $languageFrom = Language::where('code', $from)->first();
        $languageTo = Language::where('code', $to)->first();

        if (!$languageFrom || !$languageTo) {
            rZeBotUtils::message('[BotTranslateVideo] Languages not found. Remember use his code. (es, en, de, it, de, fr)', "error",'kernel');
            exit;
        }

        foreach ($scene->categories()->get() as $category) {

            $translationTo = $category->translations()->where('language_id', $languageTo->id)->first();

            // Evitamos traducir de nuevo si ya existe traducción
            if (!$translationTo->title == null || !$translationTo->permalink == null) {
                //rZeBotUtils::message('[BotTranslateVideo] Translation already exists for category_id(' .$category->id.')' . $translationTo->title, "warning", 'kernel');
                continue;
            }

            $textFrom = $category->translations()->where('language_id', $languageFrom->id)->first();

            if (!$textFrom || !$translationTo) {
                rZeBotUtils::message("[BotTranslateVideo] Translating category. Cant get 'from' and 'to' objects", "error",'kernel');
                continue;
            }

            $translationName = $this->translateText($textFrom->name, $from, $to);

            if ($translationName != false) {
                $translationTo->name = $translationName;
                $translationTo->permalink = str_slug($translationName);
                $translationTo->save();
                rZeBotUtils::message("[BotTranslateVideo] Translating category ($from => $to) | " . $textFrom->name . " - " . $translationName, "info",'kernel');
            } else {
                rZeBotUtils::message('[BotTranslateVideo] Api Translation failed for category_id: ' . $category->id, "error",'kernel');
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