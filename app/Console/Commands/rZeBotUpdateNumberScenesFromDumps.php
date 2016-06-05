<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\LanguageTag;
use App\rZeBot\rZeBotCommons;
use Illuminate\Console\Command;
use App\Model\Host;
use App\rZeBot\TwitterAPIExchange;
use DB;


class rZeBotUpdateNumberScenesFromDumps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:update:dumps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dumps information';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $channels = Channel::all();

        foreach ($channels as $channel) {
            echo PHP_EOL . "Actualizando el dump del channel $channel->name". PHP_EOL;
            $totalLines = intval(exec("wc -l '".rZeBotCommons::getDumpsFolder().$channel->file."'"));

            $channel->nvideos = $totalLines;
            $channel->save();
        }
    }
}
