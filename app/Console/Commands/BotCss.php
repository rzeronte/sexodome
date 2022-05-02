<?php

namespace App\Console\Commands;

use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
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
            $filename = $site->getCSSThemeFilename(true);
            $fullPath = "/tubeThemes/".$filename;
            Storage::disk('web')->put($fullPath, $css);

            rZeBotUtils::message("[BotCss] Updating theme for " . $site->getHost() . ": " . $fullPath, "info",'kernel');
        }
    }
}
