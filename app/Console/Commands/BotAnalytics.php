<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use App\Model\Site;
use App\Model\Analytics;
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;

class BotAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:sites:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Analytics data for all sites';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            if ($site->ga_account != '') {
                try {
                    $analyticsData = LaravelAnalyticsFacade::setSiteId('ga:' . $site->ga_account)->getVisitorsAndPageViews(5);
                    rZeBotUtils::message("[BotAnalytics] Get GA data for: " . $site->getHost(), "info",  "analytics");

                    foreach ($analyticsData as $data) {
                        $fecha = $data["date"];
                        $arrayData = [
                            "fecha"     => date("Y-m-d", strtotime($fecha)),
                            "visitors"  => $data["visitors"],
                            "pageViews" => $data["pageViews"]
                        ];

                        rZeBotUtils::message("[BotAnalytics] GA Data: Date: " . $fecha . " | Visitors: " . $arrayData["visitors"] . " | Views: " . $arrayData["pageViews"], 'info', 'analytics');

                        Analytics::where('site_id', $site->id)->where('date', $arrayData["fecha"])->delete();

                        $analytics = new Analytics();
                        $analytics->site_id = $site->id;
                        $analytics->date = $arrayData["fecha"];
                        $analytics->visitors = $arrayData["visitors"];
                        $analytics->pageviews = $arrayData["pageViews"];
                        $analytics->save();
                    }
                } catch(\Exception $e) {
                    rZeBotUtils::message("[BotAnalytics] $e->getMessage() for " . $site->getHost(), "error", 'analytics');
                }
            }
        }
    }
}
