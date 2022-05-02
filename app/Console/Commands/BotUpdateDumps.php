<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\rZeBot\sexodomeKernel;
use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;

class BotUpdateDumps extends Command
{

    protected $signature = 'zbot:dumps:update
        {--channel=false : Only update concrete channel }
    ';

    protected $description = 'Update dumps and recount his scenes';

    public function handle()
    {
        $channel = $this->option("channel");

        if ($channel !== "false") {
            $channels = Channel::where('name', $channel)->get();
        } else {
            $channels = Channel::all();
        }

        foreach ($channels as $feed) {
            rZeBotUtils::message("[BotUpdateDumps] $feed->name", "info",'kernel');
            sexodomeKernel::downloadDump($feed);
            sexodomeKernel::downloadDumpDeleted($feed);

            $filename = sexodomeKernel::getDumpsFolderTmp().$feed->file;
            $totalLines = intval(exec("wc -l '".$filename."'"));
            $feed->nvideos = $totalLines;
            $feed->save();
        }

        rZeBotUtils::message("[BotUpdateDumps] Moving dumps...", "info",'kernel');
        $cmd = "mv " . sexodomeKernel::getDumpsFolderTmp() . "* " . sexodomeKernel::getDumpsFolder();
        exec($cmd);
    }
}
