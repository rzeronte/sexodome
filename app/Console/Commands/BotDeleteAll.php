<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use Illuminate\Support\Facades\DB;

class BotDeleteAll extends Command
{
    protected $signature = 'zbot:delete:all';

    protected $description = 'Delete all scenes, tags and categories. Sites not included.';

    public function handle()
    {
        if ($this->confirm("Se eliminarán 'scenes', 'tags' y 'categories' y sus relaciones en cascada? [y|N]")) {
            DB::table('scenes')->delete();
            rZeBotUtils::message("Deleting scenes... ", "yellow", false, false, 'kernel');

            DB::table('tags')->delete();
            rZeBotUtils::message("Deleting tags... ", "yellow", false, false, 'kernel');

            DB::table('categories')->delete();
            rZeBotUtils::message("Deleting categories... ", "yellow", false, false, 'kernel');

            DB::table('pornstars')->delete();
            rZeBotUtils::message("Deleting pornstars... ", "yellow", false, false, 'kernel');
        }
    }
}