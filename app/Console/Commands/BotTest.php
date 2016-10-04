<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use DB;

class BotTest extends Command
{
    protected $signature = 'zbot:test';

    protected $description = 'test, fixes...';

    public function handle()
    {

    }
}