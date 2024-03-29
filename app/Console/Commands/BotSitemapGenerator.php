<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use Roumen\Sitemap\Sitemap;
use App\Model\Scene;
use App\Model\Site;

class BotSitemapGenerator extends Command
{
    protected $signature = 'zbot:sitemap:site {site_id}';

    protected $description = 'Generate a new sitemap for a site';

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
            rZeBotUtils::message("[BotSitemapGenerator] site_id $site_id not exists. Aborting...", "error", 'sitemaps');
            exit;
        }

        $language_id = $site->language_id;

        rZeBotUtils::message("[BotSitemapGenerator] Generating sitemap for " . $site->getSitemap(), "info",'sitemaps');

        // Scenes only for embed feeds
        $scenes = Scene::join('channels', 'channels.id', '=', 'scenes.channel_id')
            ->select('scenes.*')
            ->where('status', 1)
            ->where('channels.embed', 1)
            ->where('site_id', $site_id)
            ->orderBy('published_at', 'desc')
            ->get()
        ;

        $categories = $site->categories()->where('categories.status', 1)->get();
        $i = 0;
        foreach($categories as $category) {
            $categoryTranslation = $category->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();

            if (!$categoryTranslation) {
                rZeBotUtils::message("[BotSitemapGenerator] Ignore URL, the category " .$category->id ." doesn't have translation for language_id: $language_id", "error",'sitemaps');
            } else {
                if (strlen($categoryTranslation->permalink) > 0) {
                    $ruta = $protocol . $site->getHost() . '/' . $site->category_url . '/'.$categoryTranslation->permalink;
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
                rZeBotUtils::message("[BotSitemapGenerator] Processing page $num_scenes_chunks of scenes " . $site->getHost(),'info', 'sitemaps');

                foreach($chunk as $scene) {
                    $translation = $scene->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();

                    if ($translation) {
                        if (strlen($translation->permalink) > 0) {
                            $ruta = $protocol . $site->getHost() . '/' . $site->video_url . '/'.$translation->permalink;
                            $sitemapScenes->add($ruta, date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
                        }
                    }
                }
                $sitemapScenes->store('xml', $site->getHost().".scenes.".$num_scenes_chunks, public_path().'/sitemaps');
                rZeBotUtils::message("[BotSitemapGenerator] Saving scenes sitemap... " . $site->getSitemap(), "info",'sitemaps');
                $num_scenes_chunks++;
            }
        }

        // Pornstars
        $i = 0;
        $pornstars = $site->pornstars()->get();
        if (count($pornstars) > 0) {
            foreach ($pornstars as $pornstar) {
                $ruta = $protocol . $site->getHost() . '/' . $site->pornstar_url . '/'.$pornstar->permalink;
                $sitemapPornstars->add($ruta, date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
                $i++;
            }

            $sitemapPornstars->store('xml', $site->getHost().".pornstars", public_path().'/sitemaps');
            rZeBotUtils::message("[BotSitemapGenerator] Saving pornstars sitemap... " . $site->getSitemap(), "info",'sitemaps');
            $sitemap->addSitemap($protocol. $site->getHost() . "/sitemaps/" . $site->getHost().".pornstars.xml", date('Y-m-d\TH:i:s') );
        }

        // Home
        $sitemapDefault->add($protocol.$site->getHost(), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
        // Pornstars Root

        if (count($pornstars) > 0) {
            $sitemapDefault->add($protocol.$site->getHost().'/'.$site->pornstars_url, date('Y-m-d') . 'T00:00:00+00:00', '1.0', 'daily');
        }
        $sitemapDefault->store('xml', $site->getHost().".default", public_path().'/sitemaps');
        rZeBotUtils::message("[BotSitemapGenerator] Saving default sitemap... " . $site->getSitemap(), "info",'sitemaps');

        $sitemapCategories->store('xml', $site->getHost().".categories", public_path().'/sitemaps');
        rZeBotUtils::message("[BotSitemapGenerator] Saving categories sitemap... " . $site->getSitemap(), "info",'sitemaps');

        $sitemap->addSitemap($protocol. $site->getHost() . "/sitemaps/" . $site->getHost().".default.xml", date('Y-m-d\TH:i:s') );
        $sitemap->addSitemap($protocol. $site->getHost() . "/sitemaps/" . $site->getHost().".categories.xml", date('Y-m-d\TH:i:s') );

        if ($num_scenes_chunks !== false) {
            for ($n = 1; $n <= $num_scenes_chunks-1; $n++) {
                $sitemap->addSitemap($protocol.$site->getHost() . "/sitemaps/".$site->getHost() . ".scenes.".$n.".xml", date('Y-m-d\TH:i:s') );
            }
        }

        $sitemap->store('sitemapindex', $site->getHost(), public_path().'/sitemaps');
        rZeBotUtils::message("[BotSitemapGenerator] Saving main sitemap... " . $site->getSitemap(), "info",'sitemaps');
    }
}
