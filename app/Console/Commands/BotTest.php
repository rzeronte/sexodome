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
use Mail;

class BotTest extends Command
{
    protected $signature = 'zbot:test';

    protected $description = 'test, fixes...';

    public function handle()
    {
        $data = [];

        Mail::send('emails.verify', ['user' => "eduardo"], function ($m) use ($data) {
            $m->from('sexodomeweb@gmail.com', 'Sexodome - Tube Porn Generator');

            $m->to("eduardo.rzeronte@gmail.com", "nome")->subject('Prueba emails');
        });
    }
}