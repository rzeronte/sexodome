<?php

namespace App\Console\Commands;

use App\Model\Language;
use App\rZeBot\rZeSpinner;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;
use App\Model\Word;
use App\Model\WordSynonym;
use Goutte\Client;

class rZeBotSpinner extends Command
{
    var $creation;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:spinner:text {language} {text}
                                    {--create=false: Determine if ask for create new words}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch rZeBot for spinner a string';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $language = $this->argument('language');
        $text = $this->argument('text');

        $create = $this->option('create', "false");

        if ($create === "true") $create = true;
        if ($create === "false") $create = false;
        $this->creation = $create;

        $language = Language::where('code', $language)->first();

        $title_words = explode(" ", $text);

        foreach($title_words as $titleWord) {
            $titleWord = trim($titleWord);
            $titleWord = str_replace(",", "",$titleWord);
            if (strlen($titleWord) >= 5) {
                $this->getSynonyms($titleWord, $language);
            }
        }


        $spin = new rZeSpinner();

        $text_synonyms = $spin->addSynonyms($text, $language->id);

        $data = array(
            'src' => $text_synonyms,
            'to'  => $spin->process($text_synonyms),
        );

        print_r($data);
    }

    public function getSynonyms($src_word, $language)
    {

        $bbddWord = Word::where('word', $src_word)->where('language_id', $language->id)->first();

        // Si no existe la palabra, la creamos en el diccionario
        if (!$bbddWord) {
            if (intval($this->creation) == 1) {
                if ($this->confirm("La palabra '". $src_word . "' no existe, deseas crearla? [y|N]")) {
                    $word = new Word();
                    $word->word = $src_word;
                    $word->language_id = $language->id;
                    $word->save();
                    $bbddWord = $word;
                    echo "Creando WORD " . $src_word.PHP_EOL;
                } else {
                    echo "Ignorando " . $src_word.PHP_EOL;
                    return;
                }
            } else {
                return;
            }
        }
        if (intval($this->creation) == 1) {
            $numberCurrentSynonyms = $bbddWord->synonyms()->count();
            if ($numberCurrentSynonyms == 0) {
                $synonyms = $this->ask("Describe sinónimos para ".$src_word.":");
            } else {
                $synonyms ="";
                foreach($bbddWord->synonyms()->get() as $syn) {
                    $synonyms.=$syn->word.",";
                }
                $synonyms = substr($synonyms, 0,  -1);
                //echo "Recuperando sinónimos de la BBDD para " . $src_word .": " . $synonyms;
            }

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
            } else {
                echo "Ignorando creación de sinónimos para " . $src_word.PHP_EOL;
            }
        }
    }
}
