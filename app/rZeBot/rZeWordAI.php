<?php

namespace App\rZeBot;

class rZeWordAI
{
    public static function api($text, $quality)
    {
        $email = env('WORDAI_EMAIL');
        $pass  = env('WORDAI_PASSWORD');

        if(isset($text) && isset($quality) && isset($email) && isset($pass)) {
            $text = urlencode($text);
            $ch = curl_init('http://wordai.com/users/regular-api.php');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&output=json");
            $result = curl_exec($ch);
            curl_close ($ch);

            return $result;
        } else {
            return false;
        }
    }
}
