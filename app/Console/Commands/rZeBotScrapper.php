<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Language;
use Goutte\Client;
use App\Model\Sentence;
use App\Model\Title;
use App\rZeBot\rZeBotUtils;
use DB;

class rZeBotScrapper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:words:scrapper {language}
                                {--titles=true : Determine if scrappe titles}
                                {--sentences=true : Determine if scrappe sentences}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrappe sentences and titles';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $language  = $this->argument('language');

        $scrapeTitles    = $this->option('titles');
        $scrapeSentences = $this->option('sentences');

        $language = Language::where('code', $language)->first();

        $rZeBotUtils = new rZeBotUtils();
        $blackWordsList = $rZeBotUtils->blacWordskList;

        $url = "http://ipornogratis.xxx/page/";

        // goutte client
        $goutteClient = new Client([
            'timeout'         => 300,
            'connect_timeout' => 300,
            'allow_redirects' => true
        ]);

        $paginas = 46;
        $i = 0;

        for ($i=0; $i<=$paginas;$i++)
        {

            $crawler = $goutteClient->request('GET', $url.$i);
            $status_code = $goutteClient->getResponse()->getStatus();
            echo $status_code." ".$url.$i.PHP_EOL;
            // launch recursive crawl
            $crawler->filter('.post_format-post-format-video .titulo > a')->each(function ($node) use ($goutteClient, $language, $scrapeSentences, $scrapeTitles) {
                $url = $node->extract(array('href'));
                $url = $url[0];

                echo "===>".$url.PHP_EOL;

                $crawlerTmp = $goutteClient->request('GET', $url);
                // titiles
                if ($scrapeTitles == "true") {
                    $crawlerTmp->filter('.entry-title')->each(function ($node) use ($url, $language) {
                        //$url = $node["textContent"];
                        $texto = $node->text();

                        $title = new Title();
                        $title->title= $texto;
                        $title->language_id = $language->id;
                        $title->save();
                    });
                }

                // sentenecs
                if ($scrapeSentences == "true") {
                    $crawlerTmp->filter('.entry-content p')->each(function ($node) use ($url) {
                        //$url = $node["textContent"];

                        $texto = $node->text();

                        $sentence = new Sentence();
                        $sentence->sentence = $texto;
                        $sentence->url = $url;
                        $sentence->save();
                    });
                }
            });
            sleep(0.5);
        }
    }
}
