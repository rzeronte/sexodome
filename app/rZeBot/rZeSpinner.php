<?php

namespace App\rZeBot;

use App\Model\Word;
use App\Model\WordSynonym;
use App\Model\Language;

class rZeSpinner
{
    public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }

    public function detectarGenero($word, $language_id)
    {
        $gender = false;
        switch($language_id) {
            case 1:     // ES
                if (substr($word,-1) == "o") {
                    $gender = "male";
                } else if(substr($word,-1) == "a") {
                    $gender = "female";
                } else {
                    $gender = "neutro";
                }
                break;
            default:
                $gender = "neutro";
        }

        return $gender;
    }

    public function setGenero($word, $genero, $language_id)
    {
        $result = false;
        switch($language_id) {
            case 1:     // ES
                switch($genero) {
                    case "male":
                        $result = substr($word, 0, -1)."o";
                        break;
                    case "female":
                        $result = substr($word, 0, -1)."a";
                        break;
                    case "neutro":
                        $result = $word;
                        break;
                }
                break;
            case 2:     // EN
                break;
            case 3:     // IT
                break;
            case 4:     // FR
                break;
            case 5:     // DE
                break;
            case 6:     // NL
                break;
            case 6:     // BR
                break;

        }

        return $result;
    }

    public function addSynonyms($text, $language_id)
    {
        $rZeBotUtils = new rZeBotUtils();
        $blackWordsList = $rZeBotUtils->blacWordskList;

        $srcWords = explode(" ", $text);

        $updated_text = "";

        $cont = 0;
        foreach($srcWords as $key=>$value) {
            $titleWord = str_replace(",", "", $value);
            $titleWord = str_replace(".", "", $titleWord);
            $titleWord = str_replace("!", "", $titleWord);
            $titleWord = str_replace("?", "", $titleWord);
            $titleWord = trim($titleWord);
            $titleWord = strtolower($titleWord);

            if (!in_array($titleWord, $blackWordsList)) {
                $word = Word::where('word', 'like', $titleWord)->where('language_id', $language_id)->first();

                if ($word) {
                    $synonyms = $word->synonyms()->get();
                    if (count($synonyms) > 0) {
                        $updated_text.="{";
                        $i = 0;
                        foreach($synonyms as $synonym) {
                            $updated_text.=$synonym->word;

                            $i++;

                            if (!($i == count($synonyms))) {
                                $updated_text.="|";
                            }
                        }
                        $updated_text.="} ";
                    } else {
                        // Si no hay sinónimos ponemos la palabra directamente
                        $updated_text.=$value." ";
                    }
                } else {
                    // Buscamos en sinónimos
                        $wordSynonym = WordSynonym::where('word', 'like', $titleWord)->first();
                        if ($wordSynonym) {
//                            echo "Encontrado como sinónimo: ".$titleWord.PHP_EOL;
                            $word = Word::where('id', $wordSynonym->word_id)->first();
                            $synonyms = $word->synonyms()->get();
                            if (count($synonyms) > 0) {
                                $updated_text.="{";
                                $i = 0;
                                foreach($synonyms as $synonym) {
                                    $updated_text.=$synonym->word;

                                    $i++;

                                    if (!($i == count($synonyms))) {
                                        $updated_text.="|";
                                    }
                                }
                                $updated_text.="} ";
                            } else {
                                // Si no hay sinónimos ponemos la palabra directamente
                                $updated_text.=$value." ";
                            }
                    } else {
                        //echo "No tenemos sinonimos para " . $value.PHP_EOL;
                        $updated_text.=$value." ";
                    }
                }
            } else {

                // Si no hay sinónimos ponemos la palabra directamente
                $updated_text.=$value." ";
            }
            $cont++;
        }

        return $updated_text;
    }
}