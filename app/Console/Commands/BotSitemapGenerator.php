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
    protected $signature = 'zbot:sitemap:generate {site_id}';

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
        $sitemap = App::make("sitemap");


        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("El site_id indicado no existe", "red");
            exit;
        }

        $language_id = $site->language_id;

        $currentHost = Request::server("HTTP_HOST");

        // Scenes only for embed feeds
        $scenes = Scene::join('channels', 'channels.id', '=', 'scenes.channel_id')
            ->select('scenes.*')
            ->where('status', 1)
            ->where('channels.embed', 1)
            ->limit(40000)
            ->orderBy('published_at', 'desc')
            ->get()
        ;

        // Home
        $sitemap->add(str_replace($currentHost, $site->getHost(), route('index', [])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');

        // Categories
        $sitemap->add(str_replace($currentHost, $site->getHost(), route('categories', [])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');

        // Top Scenes
        $sitemap->add(str_replace($currentHost, $site->getHost(), route('topscenes', [])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');

        // Scenes
        $i = 0;
        foreach ($scenes as $scene) {
            $translation = $scene->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();

            if (!$translation) {
                $this->info("$i - [ERROR] Ignorando URL, la escena " .$scene->id ." no tiene traducción para el idioma id: $language_id");
            } else {
                $this->info("$i - Añadiendo al sitemap la url: ".route('video', ['permalink'=>$translation->permalink]));
                $sitemap->add(str_replace($currentHost, $site->domain, route('video', ['permalink'=>$translation->permalink])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
            }
            $i++;
        }

        $sitemap->store('xml', $site->getHost(), '');
    }
}
