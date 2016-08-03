<?php

namespace App\Console\Commands;

use App\Model\CronJob;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;
use Illuminate\Database\Eloquent\Collection;
use Log;
use Artisan;

class BotCronJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:crons:run';

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
        $cronjobs = CronJob::all();

        foreach($cronjobs as $cronjob) {
            $params = json_decode($cronjob->params);

            $paramsCommand = [
                'feed_name'    => $params->feed_name,
                'site_id'      => $params->site_id,
                '--max'        => ($params->max != "") ? $params->max: 'false',
                '--duration'   => ($params->duration != "") ? $params->duration : 'false',
                '--categories' => $params->categories,
                '--skip_create_categories' => 'true'
            ];

            Log::info('[CronJob] ' . $paramsCommand["feed_name"]);
            Artisan::call('zbot:feed:fetch', $paramsCommand);
        }
    }
}
