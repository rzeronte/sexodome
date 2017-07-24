<?php

namespace App\Console\Commands;

use App\Model\Site;
use App\rZeBot\sexodomeKernel;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Scene;

class BotDownloadThumbnails extends Command
{
    protected $signature = 'zbot:thumbnails:download
                            {--site_id=false : Only update for a site_id}
                            {--overwrite=false : Overwrite images}';

    protected $description = 'Download all thumbnails';

    public function handle()
    {
        $site_id = $this->option('site_id');
        $overwrite = $this->option('overwrite');

        if ($overwrite !== 'false') {
            $overwrite = true;
        } else {
            $overwrite = false;
        }

        if ($site_id !== "false") {

            $site = Site::find($site_id);

            if (!$site) {
                rZeBotUtils::message("[BotDownloadThumbnails] site_id: $site_id not exists", "error",'kernel');
                exit;
            }

            $sites = Site::where('id', $site_id)->get();

        } else {
            $sites = Site::all();
        }

        foreach($sites as $site) {
            rZeBotUtils::message("[BotDownloadThumbnails] Downloading thumbs for '".$site->getHost()."'", "info",'kernel');

            $scenes = Scene::select("id", "preview")->where("site_id", $site->id)->get();

            foreach($scenes as $scene) {
                sexodomeKernel::downloadThumbnail($scene->preview, $scene, $overwrite);
            }
        }
    }
}