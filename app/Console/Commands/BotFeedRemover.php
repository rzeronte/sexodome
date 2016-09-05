<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\rZeBot\rZeBotCommons;
use App\Model\Scene;
use DB;

class BotFeedRemover extends Command
{
    protected $signature = 'zbot:feed:remover';

    protected $description = "Delete from delete dumps";

    public function handle()
    {

        $sites = Site::all();
        $channels = Channel::all();
        $prefix = "deleted_";
        $chunk_limit = 50000;
        $total_processed = 0;

        foreach($channels as $channel) {
            $fileCSV = rZeBotCommons::getDumpsFolder().$prefix.$channel->file;
            $cfg = new $channel->mapping_class;
            $deleteCfg = $cfg->configDeleteFeed();

            if (!file_exists($fileCSV)) {
                rZeBotUtils::message("[ERROR] $fileCSV not exist...", "red", true, true);
            }
            rZeBotUtils::message("Processing delete dumps for " . $channel->name, "cyan", true, true);

            DB::transaction(function () use ($fileCSV, $deleteCfg, $sites, $chunk_limit, $total_processed) {
                if (($gestor = fopen($fileCSV, "r")) !== FALSE) {
                    $chunk_urls = [];
                    $chunk_iterator = 0;
                    while (($datos = fgets($gestor, 2000)) !== FALSE) {

                        // Depende del formato del CSV de borrados, obtenemos
                        // la url directamente, o partimos el string para obtener la url según la cfg del dump de borrados.
                        if ($deleteCfg["csv"]) {
                            $data = explode($deleteCfg["separator"], $datos);
                            $url_to_match = trim($data[$deleteCfg["index_url"]]);
                        } else {
                            $url_to_match = trim($datos);
                        }

                        $chunk_iterator++;
                        $chunk_urls[] = $url_to_match;
                        if ($chunk_iterator == $chunk_limit) {
                            $total_processed+=$chunk_iterator;
                            $chunk_iterator = 0;

                            $this->EraseChunkUrlInSite($chunk_urls, $total_processed);
                        }
                    }
                    // Para los que hayan quedado fuera del chunk
                    $this->EraseChunkUrlInSite($chunk_urls, $total_processed);
                }
            });
            rZeBotUtils::message("TOTAL DE BORRADOS: " . Scene::withTrashed()->whereNotNull('deleted_at')->count(), "cyan", true, true);
        }
    }

    public function EraseChunkUrlInSite(&$chunk_urls, $total_processed)
    {
        rZeBotUtils::message("[PROCESSING CHUNK] Amount ". count($chunk_urls) . " / total: "  . number_format($total_processed, 0, ".", ","), "green", true, true);

        $scenes = Scene::select('id')
            ->whereIn('iframe', $chunk_urls)
            ->get()
        ;

        if (count($scenes) > 0) {
            rZeBotUtils::message("[DELETE] Chunk of ". count($chunk_urls).", Deleting: " . count($scenes), "yellow", true, true);
            foreach ($scenes as $scene) {
                $scene->delete();
            }
        } else {
            rZeBotUtils::message("[CHUNK NOT FOUND] Chunk of ". count($chunk_urls), "yellow", true, true);
        }

        $chunk_urls = [];
    }
}