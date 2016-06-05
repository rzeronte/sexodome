<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class importScenesFromFeed extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $queueParams;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($queueParams)
    {
        $this->queueParams = $queueParams;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[importScenesFromFeed]');

        echo "feedName: " . $this->queueParams["feed_name"].PHP_EOL;
        echo "site_id: " . $this->queueParams["site_id"].PHP_EOL;

        $exitCode = \Artisan::call('rZeBot:feed:fetch', [
            'feed_name'  => $this->queueParams["feed_name"],
            'site_id'    => $this->queueParams["site_id"],
            '--max'      => $this->queueParams["max"],
            '--duration' => $this->queueParams["duration"]
        ]);
    }
}