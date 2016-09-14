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

            foreach($scenes as $scene) {
                $this->downloadThumbnail($scene->preview);
            }
        }
    }

    public function downloadThumbnail($src)
    {
        rZeBotUtils::message("[DOWNLOAD THUMBNAIL] $src", "green", true, true);

        try {
            $filepath = rZeBotCommons::getThumbnailsFolder().md5($src);

            $fp = fopen ( $filepath , 'w+');
            $ch = curl_init( str_replace(" ", "%20", $src) );  // cambiamos los espacios por %20
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
            curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            return true;

        } catch(\Exception $e) {
            rZeBotUtils::message("[ERROR DOWNLOAD THUMBNAIL] $src", "red", true, true);
        }

        rZeBotUtils::message("[DOWNLOAD THUMBNAIL] $src", "green", true, true);
    }
}