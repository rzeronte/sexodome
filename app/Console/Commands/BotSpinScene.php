<?php

namespace App\Console\Commands;

use App\Model\Language;
use App\Model\Scene;
use App\rZeBot\rZeWordAI;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;

class BotSpinScene extends Command
{
    protected $signature = 'zbot:spin:scene {scene_id}';

    protected $description = 'Spin for a scene_id';

    public function handle()
    {
        $scene_id = $this->argument('scene_id');

        $scene = Scene::find($scene_id);

        if (!$scene) {
            rZeBotUtils::message("[BotSpinScene] Scene '$scene_id' not found", 'error','kernel');
            exit;
        }

        $site = $scene->site;
        $language = Language::find($site->language_id);

        $translation = $scene->translations()->where('language_id', $site->language_id)->first();
        $title = $translation->title;
        $description = $translation->description;

        rZeBotUtils::message('[BotSpinScene] Spin Language: ' . $language->code . " | Title: " . $title . " | Desc: " . $description, 'info','kernel');

        $spinTitleData = rZeWordAI::api($title, env('WORDAI_QUALITY', 60));
        $spinTitleData = json_decode($spinTitleData, true);

        if ($spinTitleData["status"] === "Success") {
            $spintaxTitle = $spinTitleData['text'];
            $spinnedTitle = $this->process($spintaxTitle);
            $translation->title = $spinnedTitle;
        }

        if (strlen($description) > 0 ) {
            $spinDataDesc = rZeWordAI::api($description, env('WORDAI_QUALITY', 60));
            $spinDataDesc = json_decode($spinDataDesc, true);

            if ($spinDataDesc["status"] === "Success") {
                $spintaxDesc = $spinDataDesc['text'];
                $spinnedDesc = $this->process($spintaxDesc);
                $translation->description = $spinnedDesc;
            }
        } else {
            rZeBotUtils::message("[BotSpinScene] Not descripcion for scene: $scene_id", 'warning','kernel');
        }

        $scene->save();
    }

    public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }
    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}