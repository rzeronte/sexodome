<?php

namespace App\Console\Commands;

use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use Roumen\Sitemap\Sitemap;
use Illuminate\Support\Facades\App;
use App\Model\Language;
use App\Model\Scene;
use Request;
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

        $categories = $site->categories()->get();
        $i = 0;
        foreach($categories as $category) {
            $categoryTranslation = $category->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();

            if (!$categoryTranslation) {
                $this->info("$i - [ERROR] Ignorando URL, la categoría " .$category->id ." no tiene traducción para el idioma id: $language_id");
            } else {
                $this->info("$i - [SUCCESS] Url: ".route('category', ['permalink'=>$categoryTranslation->permalink]));
                $sitemapCategories->add(route('category', ['permalink'=>$categoryTranslation->permalink, "host" => $site->getHost()]), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
            }
            $i++;
        }

        // Scenes
        $num_scenes_chunks = false;
        if (count($scenes) > 0) {
            $num_scenes_chunks = 1;
            foreach ($scenes->chunk(10000) as $chunk) {
                foreach($chunk as $scene) {
                    $translation = $scene->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();

                    if (!$translation) {
                        $this->info("$i - [ERROR] Ignorando URL, la escena " .$scene->id ." no tiene traducción para el idioma id: $language_id");
                    } else {
                        $this->info("$i - [SUCCESS] Url: ".route('video', ['permalink'=>$translation->permalink]));
                        $sitemapScenes->add(route('video', ['permalink'=>$translation->permalink, "host" => $site->getHost()]), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
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
                $this->info("$i - [SUCCESS] Url: ".route('pornstar', ['permalinkPornstar' => $pornstar->permalink]));
                $sitemapPornstars->add(route('pornstar', ['permalinkPornstar' => $pornstar->permalink, "host" => $site->getHost()]), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
                $i++;
            }

            $sitemapPornstars->store('xml', $site->getHost().".pornstars", '');
            $sitemap->addSitemap('http://'. $site->getHost() . "/" . $site->getHost().".pornstars.xml", date('Y-m-d\TH:i:s') );
        }

        // Home
        $sitemapDefault->add(route('categories', ["host" => $site->getHost()]), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
        // Pornstars Root

        if (count($pornstars) > 0) {
            $sitemapDefault->add(route('pornstars', ["host" => $site->getHost()]), date('Y-m-d') . 'T00:00:00+00:00', '1.0', 'daily');
        }
        $sitemapDefault->store('xml', $site->getHost().".default", '');

        $sitemapCategories->store('xml', $site->getHost().".categories", '');
        $sitemap->addSitemap('http://'. $site->getHost() . "/" . $site->getHost().".default.xml", date('Y-m-d\TH:i:s') );
        $sitemap->addSitemap('http://'. $site->getHost() . "/" . $site->getHost().".categories.xml", date('Y-m-d\TH:i:s') );

        if ($num_scenes_chunks !== false) {
            for ($n = 1; $n <= $num_scenes_chunks-1; $n++) {
                $sitemap->addSitemap('http://'.$site->getHost() . "/".$site->getHost() . ".scenes.{$n}.xml", date('Y-m-d\TH:i:s') );
            }
        }

        $sitemap->store('sitemapindex', $site->getHost(), '');
    }
}