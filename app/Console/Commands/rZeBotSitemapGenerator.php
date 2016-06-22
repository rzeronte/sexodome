<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Roumen\Sitemap\Sitemap;
use Illuminate\Support\Facades\App;
use App\Model\Language;
use App\Model\Scene;
use Request;

class rZeBotSitemapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:sitemap:generate {language_code} {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new sitemap';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = App::make("sitemap");

        $language_code = $this->argument('language_code');
        $language_id = Language::where('code', $language_code)->first()->id;

        $domain = $this->argument('domain');

        $currentHost = Request::server("HTTP_HOST");

        // Scenes
        $scenes = Scene::join('channels', 'channels.id', '=', 'scenes.channel_id')
            ->where('status', 1)
            ->where('channels.embed','=', 1)
            ->limit(10000)
            ->orderBy('published_at', 'desc')
            ->get()
        ;

        // Home
        $sitemap->add(str_replace($currentHost, $domain, route('index', [])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');

        // Categories
        $sitemap->add(str_replace($currentHost, $domain, route('categories', [])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');

        // Top Scenes
        $sitemap->add(str_replace($currentHost, $domain, route('topscenes', [])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');

        // Scenes
        foreach ($scenes as $scene) {
            $translation = $scene->translations()->whereNotNull('permalink')->where('language_id', $language_id)->first();
            if ($translation) {
                $sitemap->add(str_replace($currentHost, $domain, route('video', ['permalink'=>$translation->permalink])), date('Y-m-d').'T00:00:00+00:00', '1.0', 'daily');
            }
        }

        $sitemap->store('xml', 'sitemap', '');
    }
}
