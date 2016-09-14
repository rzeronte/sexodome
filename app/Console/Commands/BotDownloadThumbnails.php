<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use App\rZeBot\rZeBotCommons;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Scene;
use DB;
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;
use App\Model\CategoryTranslation;
use App\Model\Category;

class BotDownloadThumbnails extends Command
{
    protected $signature = 'zbot:thumbnails:downloadAll';

    protected $description = 'Download All thumbnails';

    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            rZeBotUtils::message("[DOWNLOAD FOR ".$site->getHost()."]", "cyan", true, true);

            $scenes = Scene::select("id", "preview")->where("site_id", $site->id)->get();

            $i = 0;
            foreach($scenes as $scene) {
                $i++;
                $this->downloadThumbnail($scene->preview, $i);
            }
        }
    }

    public function downloadThumbnail($src, $i)
    {
        $filepath = rZeBotCommons::getThumbnailsFolder().md5($src).".jpg";

        if (file_exists($filepath)) {
            rZeBotUtils::message("[$i DOWNLOAD THUMBNAIL] $src", "yellow", true, true);

            return false;
        }

        try {

            $fp = fopen ( $filepath , 'w+');
            $ch = curl_init( str_replace(" ", "%20", $src) );  // cambiamos los espacios por %20
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
            curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            rZeBotUtils::message("[$i DOWNLOAD THUMBNAIL] $src", "green", true, true);

            return true;

        } catch(\Exception $e) {
            rZeBotUtils::message("[$i ERROR DOWNLOAD THUMBNAIL] $src", "red", true, true);
        }

    }
}