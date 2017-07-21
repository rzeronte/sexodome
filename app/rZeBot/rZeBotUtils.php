<?php
namespace App\rZeBot;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class rZeBotUtils
{
    static function message($message, $type = 'default', $returnLine = true, $showConsole = false, $file = false) {
        switch($type) {
            case 'green':
                $initColor = "\033[32m";
                break;
            case 'red':
                $initColor = "\033[31m";
                break;
            case 'yellow':
                $initColor = "\033[1;33m";
                break;
            case 'blue':
                $initColor = "\033[34m";
                break;
            case 'brown':
                $initColor = "\033[33m";
                break;
            case 'cyan':
                $initColor = "\033[36m";
                break;
            default:    //white
                $initColor = "\033[0m";
        }

        $endColor = "\033[0m";
        if ($file !== false) {
            $customLog = new Logger($file);
            $customLog->pushHandler(new StreamHandler(storage_path('logs/'. $file .'.log')), Logger::INFO);
            $customLog->info($initColor.$message.$endColor);
        } else {
            Log::info($initColor.$message.$endColor);
        }

        if ($showConsole !== false) {
            echo $initColor.$message.$endColor;
            if ($returnLine == true) {
                echo PHP_EOL;
            }
        }
    }

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    public static function checkRedirection301($site)
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];
        $parts = explode(".", $path);

        if ($site != false) {
            // dominio externo formato www.dominio.com
            if (count($parts) == 3 && $parts[0] == 'www') {
                return "http://".$site->getHost();
            }
        }

        return false;
    }

    public static function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' )
    {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function timesStart()
    {
        $time_start = microtime(true);

        return $time_start;
    }

    public static function timesEnd($time_start)
    {
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        rZeBotUtils::message("[TIEMPO DE EJECUCIÓN: ". gmdate("H:i:s", $execution_time)."]", "green", true, true, 'kernel');
    }

}