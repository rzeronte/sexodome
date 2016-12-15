<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;
use App\Model\Site;

class BotCheckDuplicated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:check:duplicated
                            {--site_id=false : Only update for a site_id}';

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
                ->select(array('scenes.id', DB::raw('COUNT(*) as times')))
                ->groupBy('scenes.url')
                ->having('times', '>', 1)
                ->count();

            rZeBotUtils::message($site->getHost() . ": $scenes scenes with URL (out) repeat", "yellow");
        }
    }
}
