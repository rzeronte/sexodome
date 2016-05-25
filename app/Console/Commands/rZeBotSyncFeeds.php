<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\LanguageTag;
use App\Model\SceneClick;
use App\Model\SceneTranslation;
use App\Model\Site;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Scene;
use App\Model\Tag;
use App\Model\Host;
use App\Model\SceneTag;
use App\Model\TagClick;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use DB;

class rZeBotSyncFeeds extends Command
{
    protected $signature = 'sync:feeds';

    protected $description = 'Sync Feeds betweens Universo and Network sites';

    public function handle()
    {
        $sites = Site::all();
        $channels = Channel::all();

        foreach($channels as $channel) {
            foreach($sites as $site) {
                $sql = "SELECT id FROM channels WHERE id = " . $channel->id;
                $remoteChannel = DB::connection($site->name)->select($sql);

                // create channel
                if (!$remoteChannel) {
                    rZeBotUtils::message("[WARNING] Creating channel '$channel->name' in $site->name", "green");
                    $sql_insert = "INSERT INTO channels (id, name) VALUES ($channel->id, '$channel->name')";
                    DB::connection($site->name)->insert($sql_insert);

                } else {
                    rZeBotUtils::message("[WARNING] Channel '$channel->name' already exists in $site->name", "yellow");
                    $sql_update = "UPDATE channels SET status=".$channel->status. " WHERE id=" . $channel->id . " LIMIT 1";
                    DB::connection($site->name)->insert($sql_update);
                }
            }
        }

    }
}