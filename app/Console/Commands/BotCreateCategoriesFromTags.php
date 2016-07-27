<?php

namespace App\Console\Commands;

use Faker\Provider\tr_TR\DateTime;
use Illuminate\Console\Command;
use App\Model\Category;
use App\Model\CategoryTranslation;
use App\Model\Language;
use Illuminate\Support\Facades\DB;
use App\Model\Tag;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;

class BotCreateCategoriesFromTags extends Command
{
    protected $signature = 'zbot:categories:create {site_id}
                    {--truncate=false : Determine if truncate tables}
                    {--only_truncate=false : Determine if only truncate}
                    {--min_scenes_activation=10: Determine if active category}';


    protected $description = 'Create categories from tags for a site';

    public function handle()
    {
        $site_id = $this->argument("site_id");
        $min_scenes_activation = $this->option("min_scenes_activation");
        $only_truncate = $this->option("only_truncate");
        $truncate = $this->option("truncate");

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message('[ERROR]: El site_id: '. $site_id . " no existe", "red");
            die();
        }


        if ($truncate !== "false") {
            $this->info("Truncamos tablas");
            DB::table('categories')->where("site_id", $site_id)->delete();
            if ($only_truncate !== "false") {
                $this->info("[EXIT] only_truncated");
                exit;
            }
        }

        // obtenemos todos los tags en inglés, que es el idioma referencia
        // ya que los dumps originales son en inglés.
        $englishLanguage = Language::where('code', 'en')->first();
        $tags = Tag::getTranslationSearch(false, $englishLanguage->id, $site_id);

        $languages = Language::all();

        $this->info("Escaneando para ". $tags->count() ." tags para el site ". $site->getHost());

        $tags = $tags->get();
        $i = 0;
        foreach($tags as $tag) {
            $i++;
            // Solo se convertirán en categorías tags de una sola palabra
            if (count(explode(" ", $tag->name)) == 1 && !str_contains($tag->name, array(".com", ".net", ".es", ".xxx"))) {
                echo "[ " . number_format(($i*100)/ count($tags), 0) ."% ]";

                echo "Tag: " . $tag->name;

                // Contamos el ńumero de escenas para este tags
                $countScenes = $tag->scenes()->count();

                // Si existe un umbral de escenas suficiente, el tag es una potencial categoría
                if ($this->isValidTag($tag->name)) {

                    $singular = str_singular($tag->name);
                    $plural = str_plural($tag->name);

                    echo " | Tag scenes count: $countScenes | ($singular-$plural)";

                    // Debug en pantalla para ver si el el tag es singular o plural
                    if ($tag->name == $plural) {
                        echo " -> Plural | ";
                    } else if ($tag->name == $singular) {
                        echo " -> Singular | ";
                    }

                    // Comprobamos si ya existe la categoría (las categorías solo serán plural)
                    $categoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
                        ->where('categories.site_id', '=', $site_id)
                        ->where("categories_translations.language_id", "=", $englishLanguage->id)
                        ->where("categories_translations.name", "=", utf8_encode($plural))
                        ->first()
                    ;

                    // Si no existiese, crearíamos la categoría
                    if (!$categoryTranslation) {
                        // create category
                        $newCategory = new Category();
                        $newCategory->text = $tag->name; // será plural, que es el que usamos en el where del tag
                        if ($countScenes >= $min_scenes_activation) {
                            $newCategory->status = 1;
                        } else {
                            $newCategory->status = 0;
                        }
                        $newCategory->site_id = $site_id;
                        $newCategory->save();

                        // create category languages
                        foreach($languages as $language) {
                            $newCategoryTranslation = new CategoryTranslation();
                            $newCategoryTranslation->category_id = $newCategory->id;
                            $newCategoryTranslation->language_id = $language->id;
                            @$newCategoryTranslation->thumb = $tag->scenes()->orderByRaw("RAND()")->limit(100)->first()->preview;

                            if ($language->id == $englishLanguage->id) {
                                $newCategoryTranslation->permalink = str_slug($plural);
                                $newCategoryTranslation->name = str_slug($plural);
                            }
                            $newCategoryTranslation->save();
                        }


                        // sync scenes to category
                        $ids_sync = [];

                        foreach($tag->scenes()->select("scenes.id")->get() as $video) {
                            $ids_sync[] = $video->id;
                        }

                        $this->info("[CREATE] Creando categoría $plural en http://".$site->getHost()." y sync para ".count($ids_sync)." escenas");
                        $newCategory->nscenes = count($ids_sync);
                        $newCategory->save();

                        $newCategory->scenes()->sync($ids_sync);
                    } else {
                        $plural = str_plural($tag->name);

                        // Obtenemos la categoría partiendo de la traducción
                        $category = Category::find($categoryTranslation->category_id);

                        // Obtenemos las actuales escenas asociadas a esta categoría
                        $currentCategoryScenes = $category->scenes()->get()->pluck('id');
                        $currentCategoryScenes = $currentCategoryScenes->all();

                        if ($countScenes >= $min_scenes_activation) {
                            $category->status = 1;
                        } else {
                            $category->status = 0;
                        }

                        // sync scenes to category
                        $ids_sync = [];

                        foreach($tag->scenes()->select("scenes.id")->get() as $video) {
                            $ids_sync[] = $video->id;
                        }

                        $totalIds = array_unique(array_merge($ids_sync, $currentCategoryScenes));

                        $category->nscenes = count(array_unique($totalIds));
                        $category->save();

                        $category->scenes()->sync($totalIds);

                        $this->info("[WARNING] La categoría: " . $plural. "($categoryTranslation->category_id) ya existe en ".$site->getHost() . ", sync para ".count($totalIds)." escenas...");
                    }
                } else {
                    $this->info("\033[31m | [WARNING] Ignorando categoría: " . $tag->name);
                }
            }
        }
    }

    public function isValidTag($tag) {

        if (!strlen($tag)) {
            return false;
        }

        if (is_numeric($tag)) {
            return false;
        }

        return true;
    }


}