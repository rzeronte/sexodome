<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
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
use Illuminate\Support\Facades\Artisan;


class BotCategorizeSites extends Command
{
    protected $signature = 'zbot:categorize:sites
                            {--site_id=false : Categorize only for a site_id}';

    protected $description = '(Re)Categorize scenes for one or all sites';

    public function handle()
    {
        $site_id = $this->option('site_id');

        if ($site_id !== "false") {
            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("Error el site id: $site_id no existe", "red");
                exit;
            }

            $sites = Site::where('id', $site_id)->get();

        } else {
            $sites = Site::all();
        }

        foreach($sites as $site) {
            rZeBotUtils::message("Recategorizando site_id: ". $site->getHost(), "cyan", false, false);
            $scenes = $site->scenes()->get();
            foreach ($scenes as $scene) {
                $exitCodeCmd = Artisan::call('zbot:categorize:scene', [
                    'scene_id' => $scene->id,
                ]);

            }
        }

    }
}