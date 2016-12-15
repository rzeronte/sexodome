<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;
use App\Model\Site;
use DB;

class BotCheckDuplicatedScenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:check:duplicated
                            {--site_id=false : Only update for a site_id}
                            {--remove=false : Remove duplicated}';

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
                rZeBotUtils::message("Error el site id: $site_id no existe", "red");
                exit;
            }

            $sites = Site::where('id', $site_id)->get();

        } else {
            $sites = Site::all();
        }

        foreach($sites as $site) {

            $scenes = DB::table('scenes')
                ->select(DB::raw('count(*) as scenes_count, id, url'))
                ->where('site_id', $site->id)
                ->having('scenes_count', '>', 1)
                ->groupBy('url')
                ->get();

            rZeBotUtils::message($site->getHost() . ": " .count($scenes). " scenes with URL (out) repeat", "yellow", true, true);
            if ($remove == true) {
                rZeBotUtils::message("Removing scenes in $site->getHost()", "red", true, true);
                foreach ($scenes as $s) {
                    $s->delete();
                }
            }
        }
    }
}
