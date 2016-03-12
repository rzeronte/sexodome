<?php

namespace App\Console\Commands;

use App\Model\Language;
use App\rZeBot\rZeSpinner;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\rZeBot\rZeBot;
use App\Scene;
use App\SceneTag;

class rZeBotSpinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:spinner:text {language} {text}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch rZeBot for spinner a string';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $language = $this->argument('language');
        $text = $this->argument('text');

        $language = Language::where('code', $language)->first();

        $spin = new rZeSpinner();

        $text_synonyms = $spin->addSynonyms($text, $language->id);

        echo PHP_EOL.$text_synonyms.PHP_EOL;
        echo PHP_EOL.$spin->process($text_synonyms).PHP_EOL;

    }
}
