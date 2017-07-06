<?php

namespace App\Console\Commands;

use App\Model\CategoryTag;
use App\Model\CategoryTranslation;
use App\Model\Language;
use App\Model\LanguageTag;
use App\Model\SceneCategory;
use App\Model\ScenePornstar;
use App\Model\Site;
use App\Model\Tag;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Category;
use App\Model\Pornstar;
use DB;

class BotSiteCopy extends Command
{
    protected $signature = 'zbot:site:copy {origin_site_id}';
    protected $description = 'Create new site from another';

    public function handle()
    {
        $origin_site_id = $this->argument("origin_site_id");
        $site_from = Site::find($origin_site_id);

        if (!$site_from) {
            rZeBotUtils::message("site_id: $origin_site_id not found", "red", true, true);
            return;
        }

        if (!$this->confirm('Are you sure for duplicate ('.$site_from->id.':'.$site_from->domain.')')) {
            return;
        }

        $domain_txt = $this->ask("What is the new domain?");
        $domain = Site::where('domain', $domain_txt)->first();

        if (!$domain) {
            rZeBotUtils::message("El sitio '$domain_txt' NO existe, creando... ", "green", true, true);
            $newSite = $this->createSite($domain, $site_from);
        } else {
            rZeBotUtils::message("El sitio '$domain_txt' SI existe, recuperando... ", "green", true, true);
            $newSite = $domain;

            if ($this->confirm('Do you want remove categories and tags from existing site?')) {
                $this->removePrevious($newSite);
            }
        }

        DB::transaction(function () use ( $newSite, $site_from ) {
            $this->copyTags( $newSite, $site_from );
            $this->copyCategories( $newSite, $site_from );
            $this->copyRelationshipsCategoriesTags( $newSite, $site_from );
        });
    }

    public function removePrevious($newSite)
    {
        DB::table('tags')->where('site_id', $newSite->id)->delete();
        DB::table('categories')->where('site_id', $newSite->id)->delete();
    }

    public function createSite($domain, $site_from)
    {
        $newSite = $site_from->replicate();

        $newSite->name = "Copy of " . $site_from->domain;
        $newSite->domain = $domain;
        $newSite->google_analytics = null;
        $newSite->ga_account = null;

        $newSite->push();

        return $newSite;
    }

    public function copyTags($newSite, $site_from)
    {
        foreach($site_from->tags()->get() as $tag) {
            rZeBotUtils::message("Clonando Tag: $tag->id (" . $site_from->getHost() . ':'.$site_from->id.' -> ' .$newSite->getHost(). ':'.$newSite->id.')', "green", true, false);
            $newTag = $tag->replicate();
            $newTag->site_id = $newSite->id;
            $newTag->push();

            //translations
            foreach($tag->translations()->get() as $t) {
                $newTranslation = $t->replicate();
                $newTranslation->tag_id = $newTag->id;
                $newTranslation->push();
            }
        }
    }

    public function copyCategories($newSite, $site_from)
    {
        foreach($site_from->categories()->get() as $category) {
            rZeBotUtils::message("Clonando Category: $category->id (". $site_from->getHost() . ':'.$site_from->id.' -> ' .$newSite->getHost(). ':'.$newSite->id.')', "green", true, false);
            $newCategory = $category->replicate();
            $newCategory->site_id = $newSite->id;
            $newCategory->push();

            //translations
            foreach($category->translations()->get() as $t) {
                $newTranslation = $t->replicate();
                $newTranslation->category_id = $newCategory->id;
                $newTranslation->push();
            }
        }
    }

    public function copyRelationshipsCategoriesTags($newSite, $site_from)
    {
        foreach($site_from->categories()->get() as $cat) {
            // la categoría original
            $permalink_english = $cat->translation(2);

            if (!$permalink_english) {
                print_r($permalink_english);
                rZeBotUtils::message("[copyRelationshipsCategoriesTags] No existe categoría en origen: " . $cat->id . " - original site_id: " . $site_from->id, "red", true, false);
                exit;
            }

            // Localizamos la categoría en el sitio destino
            $destinyCategory = Category::getTranslationFromPermalink($permalink_english->permalink, $newSite->id, 2);

            if (!$destinyCategory) {
                rZeBotUtils::message("[copyRelationshipsCategoriesTags] No existe categoría en destino: " . $permalink_english . " - new site_id: " . $newSite->id, "red", true, false);
                continue;
            }

            // preparamos un array con los permalinks de los tags de la categoría en inglés
            $en_tags = [];
            foreach($cat->tags()->get() as $t) {
                $trans = $t->translations()->where('language_id', 2)->first();
                $en_tags[] = $trans->permalink;
            }

            rZeBotUtils::message("Generando relaciones category-tags (".$site_from->getHost() . ':'.$site_from->id.' -> ' .$newSite->getHost(). ':'.$newSite->id.')', "cyan", true, false);

            // Buscamos tags por 'permalink' en destino y asociamos cada uno con la categoría en destino
            foreach($en_tags as $tag_txt) {
                $destinyTag = Tag::getByPermalink($tag_txt, 2, $newSite->id);

                if (!$destinyTag) {
                    rZeBotUtils::message("Error clonando relación categoría-tag (".$site_from->getHost() . ':'.$site_from->id.' -> ' .$newSite->getHost(). ':'.$newSite->id.')', "red", true, true);
                    continue;
                }

                $newRel = new CategoryTag();
                $newRel->category_id = $destinyCategory->id;
                $newRel->tag_id = $destinyTag->id;
                $newRel->save();
            }
        }
    }
}