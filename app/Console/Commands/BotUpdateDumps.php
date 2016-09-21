<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\LanguageTag;
use App\rZeBot\rZeBotCommons;
use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use App\Model\Host;
use App\rZeBot\TwitterAPIExchange;
use DB;


class BotUpdateDumps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:dumps:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dumps and recount his scenes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $channels = Channel::all();

        foreach ($channels as $feed) {
            rZeBotUtils::message("[$feed->name] $feed->url", "green", true, false);
            rZeBotUtils::downloadDump($feed);
            rZeBotUtils::downloadDumpDeleted($feed);

            $filename = rZeBotCommons::getDumpsFolderTmp().$feed->file;
            $totalLines = intval(exec("wc -l '".$filename."'"));
            $feed->nvideos = $totalLines;
            $feed->save();
        }

        rZeBotUtils::message("[MOVING DUMPS]", "green", true, false);
        $cmd = "mv " . rZeBotCommons::getDumpsFolderTmp() . "* " . rZeBotCommons::getDumpsFolder();
        exec($cmd);
    }
}