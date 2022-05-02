<?php

namespace App\Console\Commands;

use App\Model\CronJob;
use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use App\Model\Site;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class BotCronJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:crons:run
        {--site_id=false : Only update for a site_id}'
    ;

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
                rZeBotUtils::message("[BotCronJobs] site_id: $site_id not exists", "error",'cronjobs');
                exit;
            }

            rZeBotUtils::message("[BotCronJobs] Running cronjobs for " . $site->getHost(), "info",'cronjobs');
            $cronjobs = CronJob::where('site_id', $site->id)->get();
        } else{
            rZeBotUtils::message("[BotCronJobs] Running all cronjobs", "info",'cronjobs');
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
                '--categorize' => 'true'
            ];

            rZeBotUtils::message("[BotCronJobs] Launching..." . \json_encode($paramsCommand), 'info','cronjobs');
            Artisan::call('zbot:feed:fetch', $paramsCommand);
        }

        rZeBotUtils::timesEnd($start_time, 'cronjobs');
    }
}
