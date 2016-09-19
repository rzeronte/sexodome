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
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class BotCss extends Command
{
    protected $signature = 'zbot:css:update';

    protected $description = 'Generate css for all';

    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            $css = View::make('tube.commons._theme', ['site' => $site])->render();
            $filename = $site->getCSSThemeFilename();
            $fullPath = "/tubeThemes/".$filename;
            Storage::disk('web')->put($fullPath, $css);

            rZeBotUtils::message("[CSS] " . $site->getHost() . ": " . $fullPath, "yellow", true, true);
        }
    }
}