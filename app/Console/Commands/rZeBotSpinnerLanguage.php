<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Logpublish;
use App\Model\SceneClick;
use App\Model\SceneTranslation;
use App\Model\Sentence;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Title;
use App\Model\Scene;
use App\Model\Tag;
use App\Model\Host;
use App\Model\TagClick;
use App\rZeBot\rZeSpinner;
use DB;

class rZeBotSpinnerLanguage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:spinner:language {language}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spin all scenes for language from sentences. Only [esp] accepted for now.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $language = $this->argument('language');

        $veces = 0;     // depht spin level

        $scenes = Scene::all();

        $language = Language::where('code', $language)->first();

        foreach($scenes as $scene) {
            $dateTitle = $this->spinText(Title::all()->random(1)->sentence, $veces, $language);
            $dateDescription = $this->spinText(Sentence::all()->random(1)->sentence, $veces, $language);

            $translation = $scene->translations()->where('language_id', $language->id)->first();

            if (!$translation) {
                echo "[OK] Create spined translation for language".PHP_EOL;;
                $newTranslation = new SceneTranslation();
                $newTranslation->scene_id = $scene->id;
                $newTranslation->language_id = $language->id;
                $newTranslation->title = $dateTitle;
                $newTranslation->description = $dateDescription;
                $newTranslation->permalink = str_slug(substr($dateTitle, 0, 40));
                $newTranslation->save();
            } else {
                echo "[OK] Update spined translation for language".PHP_EOL;
                $translation->title = $dateTitle;
                $translation->description = $dateDescription;
                $translation->permalink = str_slug(substr($dateTitle, 0, 40));
                $translation->save();
            }
        }
    }

    public function spinText($text, $veces, $language)
    {
        for ($i=0; $i<=$veces;$i++) {
            $dataSpinned = $this->getSpinText($text, $language);

            $datos = array(
                'spintax' => $dataSpinned['spintax'],
                'spinned' => $dataSpinned['spinned'],
            );

            $text = $datos["spinned"];

            $i++;
        }

        return $text;
    }

    public function getSpinText($text, $language)
    {
        $spin = new rZeSpinner();
        $text_synonyms = $spin->addSynonyms($text, $language->id);

        return [
            'spintax' => $text_synonyms,
            'spinned' => $spin->process($text_synonyms),
        ];
    }
}
