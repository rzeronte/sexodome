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

class BotDeleteAll extends Command
{
    protected $signature = 'zbot:delete:all';

    protected $description = 'Delete all scenes, tags and categories. Sites not included.';

    public function handle()
    {
        if ($this->confirm("Se eliminarán 'scenes', 'tags' y 'categories' y sus relaciones en cascada? [y|N]")) {
            DB::table('scenes')->delete();
            rZeBotUtils::message("Deleting scenes... ", "yellow");

            DB::table('tags')->delete();
            rZeBotUtils::message("Deleting tags... ", "yellow");

            DB::table('categories')->delete();
            rZeBotUtils::message("Deleting categories... ", "yellow");
        }

    }
}