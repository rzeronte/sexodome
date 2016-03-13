<?php

namespace App\Console\Commands;

use App\Model\WordSynonym;
use App\Model\Scene;
use Illuminate\Console\Command;
use App\Model\Language;
use Goutte\Client;
use App\Model\Word;
use DB;

class rZeBotSynonyms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:words:synonyms {language}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create synonyms';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $language = $this->argument('language');

        $language = Language::where('code', $language)->first();

        $scenes = Scene::all();

        foreach($scenes as $scene) {
            $translation = $scene->translations()->where('language_id', $language->id)->first();
            if ($translation) {
                if (strlen($translation->title) > 0) {
                    $title = $translation->title;

                    $title_words = explode(" ", $title);

                    foreach($title_words as $titleWord) {
                        $titleWord = trim($titleWord);
                        $titleWord = str_replace(",", "", $titleWord);
                        $titleWord = str_replace(".", "", $titleWord);
                        $titleWord = str_replace("..", "", $titleWord);
                        $titleWord = str_replace("...", "", $titleWord);
                        $titleWord = str_replace("!", "", $titleWord);
                        $titleWord = str_replace("?", "", $titleWord);

                        if (strlen($titleWord) >= 4) {
                            $this->getSynonyms($titleWord, $language);
                        }
                    }
                }
            }
        }
    }

    public function getSynonyms($src_word, $language)
    {
        $bbddWord = Word::where('word', $src_word)->where('language_id', $language->id)->first();

        // Si no existe la palabra, la creamos en el diccionario
        if (!$bbddWord) {
            $word = new Word();
            $word->word = $src_word;
            $word->language_id = $language->id;
            $word->save();
            $bbddWord = $word;
            echo "Creando WORD " . $src_word.PHP_EOL;
            //***************************************************

            $url = "http://www.wordreference.com/sinonimos/";

            // goutte client
            $goutteClient = new Client([
                'timeout'         => 300,
                'connect_timeout' => 300,
                'allow_redirects' => true
            ]);

            // ***************************************************** launch crawler
            $crawler = $goutteClient->request('GET', $url.$src_word);

            $status_code = $goutteClient->getResponse()->getStatus();
            echo $status_code. " ".$url.$src_word." | ";

            $synonyms = ($crawler->filter('.trans > ul > li ')->count() > 0) ? $crawler->filter('.trans > ul > li')->text() : "";
            echo "Synonyms -> ". $synonyms.PHP_EOL;
            if (strlen($synonyms) > 0) {
                $words = explode(",", $synonyms);
                $z = 0;
                foreach ($words as $txtWord) {
                    if ($z<=4) {
                        $txtWord = trim($txtWord);
                        $synonyms = $bbddWord->synonyms()->where('word', $txtWord)->first();
                        // Si el sinónimo no existe parala palabra lo creamos
                        if (!$synonyms) {
                            $sinonimo = new WordSynonym();
                            $sinonimo->word_id = $bbddWord->id;
                            $sinonimo->word = $txtWord;
                            $sinonimo->save();
                            echo "Creando WORD_SYNONYM " . $txtWord.PHP_EOL;
                        }
                    }
                    $z++;
                }
            }
            sleep(3);

        }

    }
}
