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
        $srcWords = explode(" ", $text);

        $updated_text = "";

        $cont = 0;
        foreach($srcWords as $key=>$value) {
            $word = Word::where('word', 'like', $value)->where('language_id', $language_id)->first();

            if ($word) {
                $synonyms = $word->synonyms()->get();
                if (count($synonyms) > 0) {
                    $updated_text.="{";
                    $i = 0;
                    foreach($synonyms as $synonym) {
                        $genero = false;
                        $genero2 = false;

                        if (substr($synonym->word, -1) != "s") {
                            if (isset($srcWords[$cont+1])) {
                                $genero = $this->detectarGenero($srcWords[$cont+1], $language_id);
                            }
                        }

                        if (substr($synonym->word, -1) != "s") {
                            if (isset($srcWords[$cont-1])) {
                                $genero2 = $this->detectarGenero($srcWords[$cont-11], $language_id);
                            }
                        }

                        if ($genero !== false) {
                            $updated_text.=$this->setGenero($synonym->word, $genero, $language_id);
                        } else if ($genero2 !== false){
                            $updated_text .= $this->setGenero($synonym->word, $genero2, $language_id);
                        } else{
                            $updated_text.=$synonym->word;
                        }

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

            $cont++;
        }

        return $updated_text;
    }
}