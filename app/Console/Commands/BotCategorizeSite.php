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
            rZeBotUtils::message("[BotCategorizeSite] El site id: $site_id no existe", "error",'kernel');
            exit;
        }

        rZeBotUtils::message("[BotCategorizeSite] Recategorizando ". $site->getHost(), "info",'kernel');

        $scenes = $site->scenes()->select('id')->get();
        foreach ($scenes as $scene) {
            Artisan::call('zbot:categorize:scene', [
                'scene_id' => $scene->id,
            ]);

        }
    }
}