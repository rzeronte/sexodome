<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use Roumen\Sitemap\Sitemap;
use App\Model\Scene;
use App\Model\Site;

class BotSitemapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:sitemap:site {site_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new sitemap for a site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = new Sitemap(["use_styles" => false]);
        $sitemapDefault = new Sitemap(["use_styles" => false]);
        $sitemapCategories = new Sitemap(["use_styles" => false]);
        $sitemapPornstars = new Sitemap(["use_styles" => false]);
        $sitemapScenes = new Sitemap(["use_styles" => false]);

        $site_id = $this->argument('site_id');
        $site = Site::find($site_id);
        $protocol = "http://";

        if (!$site) {
            rZeBotUtils::message("El site_id indicado no existe", "red");
            exit;
        }

        $language_id = $site->language_id;

        rZeBotUtils::message("Generating sitemap for " . $site->getSitemap(), "green", true, true);

        // Scenes only for embed feeds
        $scenes = Scene::join('channels', 'channels.id', '=', 'scenes.channel_id')
            ->select('scenes.*')
            ->where('status', 1)
            ->where('channels.embed', 1)
            ->where('site_id', $site_id)
            ->orderBy('published_at', 'desc')
            ->get()
        ;

        $categories = $site->categories()->where('status', 1)->get();
        $i = 0;
        foreach($categories as $category) {
            $categoryTranslation = $category->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();

            if (!$categoryTranslation) {
                $this->info("$i - [ERROR] Ignorando URL, la categoría " .$category->id ." no tiene traducción para el idioma id: $language_id");
            } else {
                if (strlen($categoryTranslation->permalink) > 0) {
                    $ruta = $protocol . $site->getHost() . '/' . $site->category_url . '/'.$categoryTranslation->permalink;
                    $this->info("$i - [SUCCESS] Url: " . $ruta);
                    $sitemapCategories->add($ruta, date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
                }
            }
            $i++;
        }

        // Scenes
        $num_scenes_chunks = false;
        if (count($scenes) > 0) {
            $num_scenes_chunks = 1;
            foreach ($scenes->chunk(20000) as $chunk) {
                $sitemapScenes = new Sitemap(["use_styles" => false]);

                $this->info("Procesando página $num_scenes_chunks de videos en " . $site->getHost() . " - [SUCCESS] ");
                foreach($chunk as $scene) {
                    $translation = $scene->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();

                    if ($translation) {
                        if (strlen($translation->permalink) > 0) {
                            $ruta = $protocol . $site->getHost() . '/' . $site->video_url . '/'.$translation->permalink;
                            $sitemapScenes->add($ruta, date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
                        }
                    }
                }
                $sitemapScenes->store('xml', $site->getHost().".scenes.".$num_scenes_chunks, '');
                $num_scenes_chunks++;
            }
        }

        // Pornstars
        $i = 0;
        $pornstars = $site->pornstars()->get();
        if (count($pornstars) > 0) {
            foreach ($pornstars as $pornstar) {
                $ruta = $protocol . $site->getHost() . '/' . $site->pornstar_url . '/'.$pornstar->permalink;
                $this->info("$i - [SUCCESS] Url: " . $ruta);
                $sitemapPornstars->add($ruta, date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
                $i++;
            }

            $sitemapPornstars->store('xml', $site->getHost().".pornstars", '');
            $sitemap->addSitemap($protocol. $site->getHost() . "/" . $site->getHost().".pornstars.xml", date('Y-m-d\TH:i:s') );
        }

        // Home
        $sitemapDefault->add($protocol.$site->getHost(), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
        // Pornstars Root

        if (count($pornstars) > 0) {
            $sitemapDefault->add($protocol.$site->getHost().'/'.$site->pornstars_url, date('Y-m-d') . 'T00:00:00+00:00', '1.0', 'daily');
        }
        $sitemapDefault->store('xml', $site->getHost().".default", '');

        $sitemapCategories->store('xml', $site->getHost().".categories", '');
        $sitemap->addSitemap($protocol. $site->getHost() . "/" . $site->getHost().".default.xml", date('Y-m-d\TH:i:s') );
        $sitemap->addSitemap($protocol. $site->getHost() . "/" . $site->getHost().".categories.xml", date('Y-m-d\TH:i:s') );

        if ($num_scenes_chunks !== false) {
            for ($n = 1; $n <= $num_scenes_chunks-1; $n++) {
                $sitemap->addSitemap($protocol.$site->getHost() . "/".$site->getHost() . ".scenes.".$n.".xml", date('Y-m-d\TH:i:s') );
            }
        }

        $sitemap->store('sitemapindex', $site->getHost(), '');
    }
}