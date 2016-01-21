<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\rZeBot\rZeBot;
use App\Scene;
use App\SceneTag;

class rZeBotClicks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:hunting:clicks
                            {depthMaxLevel=5 : Determine depth for hunting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch rZeBot for click myself';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $intro = "*> Liberando rZeBot Clicks v.1.0 ".date('Y-m-d H:m:s')." *****************************".PHP_EOL.
            "***************************************************************************".PHP_EOL;
        $this->comment($intro.PHP_EOL);

        // bot creation
        $bot = new rZeBot($this->argument('depthMaxLevel'), 'hosts');
        $bot->initClicksMyself();
    }
}
