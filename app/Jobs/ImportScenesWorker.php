<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class ImportScenesWorker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $queueParams;
    public $tries = 5;
    public $timeout = 600;

    public function __construct($queueParams)
    {
        $this->queueParams = $queueParams;
    }

    public function handle()
    {
        Log::info('[ImportScenesWorker]');

        $paramsCommand = [
            'feed_name'    => $this->queueParams["feed_name"],
            'site_id'      => $this->queueParams["site_id"],
            '--max'        => ($this->queueParams["max"] != "") ? $this->queueParams["max"]: 'false',
            '--duration'   => ($this->queueParams["duration"] != "") ? $this->queueParams["duration"] : 'false',
            '--tags' => $this->queueParams["tags"],
        ];

        Artisan::call('zbot:feed:fetch', $paramsCommand);
    }

    public function failed(\Exception $exception)
    {
        Log::info('[ERROR] ' . $exception->getMessage());
    }
}
