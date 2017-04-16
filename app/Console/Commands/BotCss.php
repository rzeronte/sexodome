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
    protected $signature = 'zbot:css:update
                        {--site_id=false : Only update concrete site }
    ';


    protected $description = 'Generate css for all';

    public function handle()
    {
        $site_id = $this->option("site_id");

        if ($site_id !== "false") {
            $sites = Site::where('id', $site_id)->get();
        } else {
            $sites = Site::all();
        }

        foreach($sites as $site) {
            $css = View::make('tube.commons._theme', ['site' => $site])->render();
            $filename = $site->getCSSThemeFilename();
            $fullPath = "/tubeThemes/".$filename;
            Storage::disk('web')->put($fullPath, $css);

            rZeBotUtils::message("[CSS] " . $site->getHost() . ": " . $fullPath, "yellow", true, true);
        }
    }
}