<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\rZeBot\sexodomeKernel;
use App\Model\Scene;
use Illuminate\Support\Facades\DB;

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
            $fileCSV = sexodomeKernel::getDumpsFolder().$prefix.$channel->file;
            $cfg = new $channel->mapping_class;
            $deleteCfg = $cfg->configDeleteFeed();

            if (!file_exists($fileCSV)) {
                rZeBotUtils::message("[ERROR] $fileCSV not exist...", "red", false, false, 'remover');

                continue;
            }
            rZeBotUtils::message("Processing delete dumps for " . $channel->name, "cyan", false, false, 'remover');

            DB::transaction(function () use ($fileCSV, $deleteCfg, $sites, $chunk_limit, $total_processed) {
                if (($gestor = fopen($fileCSV, "r")) !== FALSE) {

                    $chunk_urls = [];
                    $chunk_ids = [];
                    $chunk_iterator = 0;

                    rZeBotUtils::message("Delete dump type:  " . $deleteCfg["type"], "yellow", false, false, 'remover');

                    while (($datos = fgets($gestor, 2000)) !== FALSE) {

                        //*********************************************************** BORRADO POR 'URL'
                        if ($deleteCfg["type"] == "url") {

                            //*********************************************************** csv
                            if ($deleteCfg["csv"]) {
                                $data = explode($deleteCfg["separator"], $datos);
                                $url_to_match = trim($data[$deleteCfg["index_url"]]);
                            } else {
                                //*********************************************************** url en crudo
                                $url_to_match = trim($datos);
                            }

                            $chunk_urls[] = $url_to_match;

                        } elseif ($deleteCfg["type"] == "id") { //********************** BORRADO POR 'ID'

                            //*********************************************************** csv
                            if ($deleteCfg["csv"]) {
                                $data = explode($deleteCfg["separator"], $datos);
                                $id_to_match = trim($data[$deleteCfg["index_url"]]);
                            } else {
                                //*********************************************************** id
                                $id_to_match = trim($datos);
                            }

                            $chunk_ids[] = $id_to_match;
                        }

                        $chunk_iterator++;

                        // cada $chunk_limit vaciamos, ya sea array de urls o de ids
                        if ($chunk_iterator == $chunk_limit) {

                            $total_processed+=$chunk_iterator;
                            $chunk_iterator = 0;

                            $this->EraseChunkUrlInSite($chunk_urls, $total_processed);
                            $this->EraseChunkIdInSite($chunk_ids, $total_processed);
                        }

                    }
                    // Para los que hayan quedado fuera del chunk
                    $this->EraseChunkUrlInSite($chunk_urls, $total_processed);
                    $this->EraseChunkIdInSite($chunk_ids, $total_processed);
                }
            });
            rZeBotUtils::message("TOTAL DE BORRADOS: " . Scene::withTrashed()->whereNotNull('deleted_at')->count(), "cyan", false, false, 'remover');
        }
    }

    public function EraseChunkIdInSite($chunk_ids, $total_processed)
    {

    }

    public function EraseChunkUrlInSite(&$chunk_urls, $total_processed)
    {
        rZeBotUtils::message("[PROCESSING CHUNK] Amount ". count($chunk_urls) . " / total: "  . number_format($total_processed, 0, ".", ","), "green", false, false, 'remover');

        $scenes = Scene::select('id')
            ->whereIn('iframe', $chunk_urls)
            ->get()
        ;

        if (count($scenes) > 0) {
            rZeBotUtils::message("[DELETE] Chunk of ". count($chunk_urls).", Deleting: " . count($scenes), "yellow", false, false, 'remover');
            foreach ($scenes as $scene) {
                $scene->delete();
            }
        } else {
            rZeBotUtils::message("[CHUNK NOT FOUND] Chunk of ". count($chunk_urls), "yellow", false, false, 'remover');
        }

        $chunk_urls = [];
    }
}