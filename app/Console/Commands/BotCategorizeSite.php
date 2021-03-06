<?php

namespace App\Console\Commands;

use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use Illuminate\Support\Facades\Artisan;

class BotCategorizeSite extends Command
{
    protected $signature = 'zbot:categorize:site {site_id}';

    protected $description = 'Categorize scenes for one site in function his tags';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("[BotCategorizeSite] site_id: $site_id not exists", "error",'kernel');
            exit;
        }

        rZeBotUtils::message("[BotCategorizeSite] Recategorizing for ". $site->getHost(), "info",'kernel');

        foreach ($site->scenes()->select('id')->get() as $scene) {
            Artisan::call('zbot:categorize:scene', [
                'scene_id' => $scene->id,
            ]);
        }
    }
}