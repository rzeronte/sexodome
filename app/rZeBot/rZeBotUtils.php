<?php
namespace App\rZeBot;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\Log;

class rZeBotUtils
{
    static function message($message, $type = 'default', $file = false, $returnLine = true, $showConsole = false)
    {
        $msg = $message;

        if ($file !== false) {
            $customLog = new Logger($file);
            $customLog->pushHandler(new StreamHandler(storage_path('logs/'. $file .'.log')));

            if ($type == 'info') {
                $customLog->info("\033[32m".$msg."\033[0m");
            } else if ($type == 'error') {
                $customLog->error("\033[31m".$msg."\033[0m");
            } else if ($type == 'warning') {
                $customLog->warning("\033[1;33m".$msg."\033[0m");
            }
        } else {
            if ($type == 'info') {
                Log::info("\033[32m".$msg."\033[0m");
            } else if ($type == 'error') {
                Log::error("\033[31m".$msg."\033[0m");
            } else if ($type == 'warning') {
                Log::warning("\033[1;33m".$msg."\033[0m");
            }
        }

        // Si hay salida por consola
        if ($showConsole !== false) {
            echo $message;
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

    public static function timesEnd($time_start, $type = 'info')
    {
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        rZeBotUtils::message("[Tiempo de ejecución: ". gmdate("H:i:s", $execution_time)."]", $type,'kernel');
    }

}