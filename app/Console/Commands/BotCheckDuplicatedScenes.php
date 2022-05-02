<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use App\Model\Scene;
use App\Model\Site;
use Illuminate\Support\Facades\DB;

class BotCheckDuplicatedScenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:check:duplicated
        {--site_id=false : Only update for a site_id}
        {--remove=false : Remove duplicated}'
    ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix for duplicated scenes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $site_id = $this->option('site_id');
        $remove = $this->option('remove');

        if ($remove !== 'false') {
            $remove = true;
        } else {
            $remove = false;
        }

        if ($site_id !== "false") {

            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("[BotCheckDuplicatedScenes] site_id: $site_id not exists", "error", 'kernel');
                exit;
            }

            $sites = Site::where('id', $site_id)->get();

        } else {
            $sites = Site::all();
        }

        foreach($sites as $site) {

            $scenes = Scene::select(DB::raw('count(*) as scenes_count, id, url'))
                ->where('site_id', $site->id)
                ->having('scenes_count', '>', 1)
                ->groupBy('url')
                ->get();

            rZeBotUtils::message("[BotCheckDuplicatedScenes] " . $site->getHost() . ": " .count($scenes). " scenes with URL (out) repeat", "info",'kernel');
            if ($remove == true) {
                rZeBotUtils::message("[BotCheckDuplicatedScenes] Removing scenes in " . $site->getHost(), "info",'kernel');
                foreach ($scenes as $s) {
                    $s->delete();
                }
            }
        }
    }
}
