<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\rZeBot\sexodomeKernel;
use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;

class BotUpdateDumps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:dumps:update
                    {--channel=false : Only update concrete channel }
    ';

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