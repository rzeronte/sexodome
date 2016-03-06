<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\rZeBot\rZeBot;
use App\Scene;
use App\SceneTag;
use App\Model\Site;
use App\Model\Analytics;
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;

class rZeBotAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Captura las datos de Google Analytics';

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
                $analyticsData = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getVisitorsAndPageViews(2);

                foreach ($analyticsData as $data) {

                    $fecha = $data["date"];
                    $arrayData = array(
                        "fecha"     => date("Y-m-d", strtotime($fecha)),
                        "visitors"  => $data["visitors"],
                        "pageViews" => $data["pageViews"]
                    );

                    Analytics::where('site_id', $site->id)->where('date', $arrayData["fecha"])->delete();

                    $analytics = new Analytics();
                    $analytics->site_id = $site->id;
                    $analytics->date = $arrayData["fecha"];
                    $analytics->visitors = $arrayData["visitors"];
                    $analytics->pageviews = $arrayData["pageViews"];
                    $analytics->save();

                }

            }
        }
    }
}
