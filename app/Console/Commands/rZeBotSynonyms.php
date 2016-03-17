<?php

namespace App\Console\Commands;

use App\Model\WordSynonym;
use App\Model\Scene;
use Illuminate\Console\Command;
use App\Model\Language;
use Goutte\Client;
use App\Model\Word;
use App\rZeBot\rZeBotUtils;
use DB;

class rZeBotSynonyms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:words:synonyms {source} {language}';

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
        $source = $this->argument('source');
        $language = Language::where('code', $language)->first();

        $rZeBotUtils = new rZeBotUtils();
        $blackWordsList = $rZeBotUtils->blacWordskList;

        $scenes = Scene::all();

        if (($gestor = fopen($source, "r")) !== FALSE) {
            while (!feof($gestor)) {
                $datos = fread($gestor, 8192);
                $title_words = explode(" ", $datos);
                foreach($title_words as $titleWord) {
                    $titleWord = trim($titleWord);
                    $titleWord = str_replace(",", "", $titleWord);
                    $titleWord = str_replace(".", "", $titleWord);
                    $titleWord = str_replace("..", "", $titleWord);
                    $titleWord = str_replace("...", "", $titleWord);
                    $titleWord = str_replace("!", "", $titleWord);
                    $titleWord = str_replace("¡", "", $titleWord);
                    $titleWord = str_replace("?", "", $titleWord);
                    $titleWord = str_replace("¿", "", $titleWord);
                    $titleWord = str_replace(")", "", $titleWord);
                    $titleWord = str_replace("(", "", $titleWord);
                    $titleWord = $titleWord;
                    $titleWord = strtolower($titleWord);
                    if (!in_array($titleWord, $blackWordsList)) {
                        if (strlen($titleWord) >=4) {
                            $this->getSynonyms($titleWord, $language);
                        }
                    }
                }
            }
        }
    }

    public function isSpanishVerb($titleWord)
    {
        $terminacion = substr($titleWord, 0 -2);

        if ($terminacion == "ar" || $terminacion == "er" || $terminacion == "ir")
        {
            return true;
        }

        return false;
    }

    public function getSynonyms($src_word, $language)
    {
        $bbddWord = Word::where('word', $src_word)->where('language_id', $language->id)->first();

        // Si no existe la palabra, la creamos en el diccionario
        if (!$bbddWord) {
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

            if (strlen($synonyms) > 0) {
                $words = explode(",", $synonyms);
                $z = 0;

                // Si el sinónimo no existe parala palabra lo creamos
                echo "Creando WORD " . $src_word." | ";
                $word = new Word();
                $word->word = $src_word;
                $word->language_id = $language->id;
                $word->save();
                echo "Synonyms -> ". PHP_EOL.$synonyms.PHP_EOL;

                foreach ($words as $txtWord) {
                    if ($z<=2) {    // Solo nos quedamos con 4 si hay
                        $txtWord = trim($txtWord);

                        sleep(2);

                        $sinonimo = new WordSynonym();
                        $sinonimo->word_id = $word->id;
                        $sinonimo->word = $txtWord;
                        $sinonimo->save();
                        //echo "Creando WORD_SYNONYM " . $txtWord.PHP_EOL;
                    }
                    $z++;
                }
                echo PHP_EOL;
            }
        }
    }

    public function loadWordsAndHisSynonyms($source)
    {
        //****************
        if (($gestor = fopen($source, "r")) !== FALSE) {
            while (!feof($gestor)) {
                $datos = fgets($gestor, 500);
                $data = explode(":", $datos);

                if (isset($data[1])) {
                    $newData = array(
                        'word' => $data[0],
                        'sinonimos' => explode(",", $data[1]),
                    );
                    $bbddWord = Word::where('word', trim($data[0]))->where('language_id', $language->id)->first();

                    if (!$bbddWord) {
                        $word = new Word();
                        $word->word = trim($data[0]);
                        $word->language_id = $language->id;
                        $word->save();

                        foreach($newData['sinonimos'] as $sin) {
                            $newSin = new WordSynonym();
                            $newSin->word_id = $word->id;
                            $newSin->word = $sin;
                            $newSin->save();
                        }
                    }
                }
            }
        }
    }
}
