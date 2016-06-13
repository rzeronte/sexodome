<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\LanguageTag;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Site;
use App\Model\InfoJobs;

class rZeBotFeedFetcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:feed:fetch {feed_name} {site_id}
                            {--max=false : Number max scenes to import}
                            {--tags=false : Only tags imported}
                            {--categories=false : Only categories imported}
                            {--rate=false : Only rate min imported}
                            {--views=false : Only views min imported}
                            {--only_update=false : Only update scenes }
                            {--duration=false : Only duration min imported}
                            {--job=false : Infojob Id}
                            {--test=false : Test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch scenes from feeds to one site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $feed_name     = $this->argument('feed_name');
        $site_id       = $this->argument('site_id');

        $max         = $this->option('max');
        $tags        = $this->option('tags', false);
        $categories  = $this->option('categories');
        $rate        = $this->option('rate');
        $minViews    = $this->option('views');
        $minDuration = $this->option('duration');
        $only_update = $this->option('only_update');
        $test        = $this->option('test');
        $job         = $this->option('job');

        $tags       = $this->parseTagsOption($tags);
        $categories = $this->parseCategoriesOption($categories);

        // get feed
        $feed = Channel::where("name", "=", $feed_name)->first();

        if (!$feed) {
            rZeBotUtils::message("[ERROR] El feed '$feed_name' indicado no existe. Abortando ejecución.", "red");
            exit;
        }

        // check site
        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("[ERROR] El sitio '$site_id' indicado no existe. Abortando ejecución.", "red");
            exit;
        }

        // instance class dynamically from mapping_class field in bbdd
        $cfg = new $feed->mapping_class;

        // Info debug
        if ($job !== "false") {
            rZeBotUtils::message('Job: '. $job, "green");
        }

        rZeBotUtils::parseCSV(
            $site_id,
            $feed,
            $max,
            $cfg->mappingColumns(),
            $cfg->configFeed(),
            $tags,
            $categories,
            $only_update,
            $rate,
            $minViews,
            $minDuration,
            $default_status = env("DEFAULT_FETCH_STATUS", 1),
            $test
        );

        // delete infojob
        if ($job !== "false") {
            rZeBotUtils::message('Finalizamos InfoJob: '. $job, "yellow");
            $infojob = InfoJobs::find($job);
            $infojob->finished = true;
            $infojob->finished_at = date("Y-m-d H:i:s");
            $infojob->save();
        }
    }

    public function parseTagsOption($tags)
    {
        if ($tags !== 'false') {
            $tags = explode(",", $tags);
        } else {
            $tags = false;
        }

        return $tags;
    }

    public function parseCategoriesOption($categories)
    {
        if ($categories !== 'false') {
            $categories = explode(",", $categories);
            echo "Categories selected:".PHP_EOL;
            print_r($categories);
        } else {
            $categories = false;
        }

        return $categories;
    }
}