<?php

namespace App\Console\Commands;

use App\Model\CronJob;
use App\rZeBot\rZeBotUtils;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;
use App\Model\Site;
use Log;
use Artisan;

class BotCronJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:crons:run
                            {--site_id=false : Only update for a site_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch cron works';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start_time = rZeBotUtils::timesStart();

        $site_id = $this->option('site_id');
        if ($site_id !== "false") {
            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("Error el site id: $site_id no existe", "red");
                exit;
            }
            rZeBotUtils::message("Error el site id: $site_id no existe", "red");

            rZeBotUtils::message("Running cronjobs for " . $site->getHost(), "green", true, true);
            $cronjobs = CronJob::where('site_id', $site->id)->get();
        } else{
            rZeBotUtils::message("Running all cronjobs", "green", true, true);
            $cronjobs = CronJob::all();
        }

        foreach($cronjobs as $cronjob) {
            $params = json_decode($cronjob->params);

            $paramsCommand = [
                'feed_name'    => $params->feed_name,
                'site_id'      => $params->site_id,
                '--max'        => ($params->max != "") ? $params->max: 'false',
                '--duration'   => ($params->duration != "") ? $params->duration : 'false',
                '--tags'       => $params->tags,
                '--create_categories_from_tags' => 'true'
            ];

            Log::info('[CronJob] ' . $paramsCommand["feed_name"]);
            Artisan::call('zbot:feed:fetch', $paramsCommand);
        }

        rZeBotUtils::timesEnd($start_time);
    }
}
