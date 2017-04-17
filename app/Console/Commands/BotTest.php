<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use DB;
use Mail;

class BotTest extends Command
{
    protected $signature = 'zbot:test';

    protected $description = 'test, fixes...';

    public function handle()
    {
        $category_ids = DB::table('categories2langs')->select('category_id')->groupBy('category_id')->get();

        $categories = [];
        foreach($category_ids as $category) {
            $category_id = $category->category_id;
            $translations = DB::table('categories2langs')->where('category_id', $category_id)->get();
            $category = [];
            foreach($translations as $translation) {
                $text_lng = false;
                switch ($translation->lang_id) {
                    case "1":
                        $text_lng = "text_en";
                        break;
                    case "2":
                        $text_lng = "text_es";
                        break;
                    case "3":
                        $text_lng = "text_it";
                        break;
                    case "7":
                        $text_lng = "text_de";
                        break;
                    case "5":
                        $text_lng = "text_fr";
                        break;
                    case "4":
                        $text_lng = "text_br";
                        break;
                }

                if ($text_lng) {
                    $category[$text_lng] = $translation->name;
                }
            }
            $categories[] = $category;
        }

        file_put_contents("categories_bbdd.json", \GuzzleHttp\json_encode($categories));
    }
}


