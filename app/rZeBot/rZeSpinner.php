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

    public function addSynonyms($text, $language_id)
    {
        $srcWords = explode(" ", $text);

        $updated_text = "";

        foreach($srcWords as $srcWord) {
            $word = Word::where('word', 'like', $srcWord)->where('language_id', $language_id)->first();

            if ($word) {
                $synonyms = $word->synonyms()->get();
                if (count($synonyms) > 0) {
                    $updated_text.="{";
                    $i = 0;
                    foreach($synonyms as $synonym) {
                        $updated_text.=$synonym->word." ";
                        $i++;

                        if (!($i == count($synonyms))) {
                            $updated_text.="|";
                        }
                    }
                    $updated_text.="}";
                } else {
                    // Si no hay sinónimos ponemos la palabra directamente
                    $updated_text.=$srcWord." ";
                }
            } else {
                $updated_text.=$srcWord." ";
            }
        }

        return $updated_text;
    }
}