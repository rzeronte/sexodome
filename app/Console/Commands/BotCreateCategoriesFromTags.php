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
use Artisan;

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
        $timer = time();
        foreach($tags as $tag) {
            $i++;
            // Solo se convertirán en categorías tags de una sola palabra
            if ($this->isValidTag($tag->name)) {
                rZeBotUtils::message("[" . number_format(($i*100)/ count($tags), 0) ."%] ". gmdate("H:i:s", (time()-$timer)) . " |", "white", false);

                rZeBotUtils::message(" " . str_pad($tag->name, 20, "."), "yellow", false);

                // Contamos el ńumero de escenas para este tags
                $countScenes = $tag->scenes()->count();

                $singular = str_singular($tag->name);
                $plural = str_plural($tag->name);

                echo str_pad(" | scenes count: $countScenes", 25, ".");
                echo str_pad(" | [$singular]/[$plural]", 40, ".");

                // Debug en pantalla para ver si el el tag es singular o plural
                if ($tag->name == $plural) {
                    echo str_pad(" | Plural", 11, ".");
                } else if ($tag->name == $singular) {
                    echo str_pad(" | Singular", 11, ".");
                }

                // Comprobamos si ya existe la categoría (las categorías solo serán plural)
                $categoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
                    ->select('categories_translations.id')
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
                        //@$newCategoryTranslation->thumb = $tag->scenes()->orderByRaw("RAND()")->limit(100)->first()->preview;

                        if ($language->id == $englishLanguage->id) {
                            $newCategoryTranslation->permalink = str_slug($plural);
                            $newCategoryTranslation->name = str_slug($plural);
                        }
                        $newCategoryTranslation->save();
                    }

                    // sync scenes to category
                    $ids_sync = $tag->scenes()->select('scenes.id')->get()->pluck('id');
                    $ids_sync = $ids_sync->all();

                    rZeBotUtils::message(str_pad(" | [CREATE CATEGORY] '$plural'", 45, "."), "green", false);
                    rZeBotUtils::message(str_pad(" | Sync ".count($ids_sync), 12, "."), "yellow", false);
                    $newCategory->nscenes = count($ids_sync);
                    $newCategory->save();

                    $newCategory->scenes()->sync($ids_sync);
                } else {

                    // Obtenemos la categoría partiendo de la traducción
                    $category = Category::find($categoryTranslation->category_id);
                    if (!$category) {
                        rZeBotUtils::message(" | [CATEGORY NOT FOUND FROM HIS TRANSLATION] " . $plural. " | (" . $categoryTranslation->category_id . ")", "red");
                        continue;
                    }

                    // Obtenemos las actuales escenas asociadas a esta categoría
                    $currentCategoryScenes = $category->scenes()->select('scenes.id')->get()->pluck('id');
                    $currentCategoryScenes = $currentCategoryScenes->all();

                    if ($countScenes >= $min_scenes_activation) {
                        $category->status = 1;
                    } else {
                        $category->status = 0;
                    }

                    // sync scenes to category
                    $ids_sync = $tag->scenes()->select('scenes.id')->get()->pluck('id');
                    $ids_sync = $ids_sync->all();

                    $totalIds = array_unique(array_merge($ids_sync, $currentCategoryScenes));

                    $category->nscenes = count(array_unique($totalIds));
                    $category->save();

                    $category->scenes()->sync($totalIds);

                    rZeBotUtils::message(" | [ALREADY EXISTS] " . $plural. " | (" . $categoryTranslation->category_id . ") | sync " . count($totalIds), "red", false);
                }
            } else {
                rZeBotUtils::message("[WARNING] Ignorando categoría: " . $tag->name, "red", false);
            }
            echo PHP_EOL;
        }

        Artisan::call('zbot:categories:thumbnails', [
            'site_id' => $site_id
        ]);
    }

    /**
     * Check if tag is valid for Category
     *
     * @param $tag
     * @return bool
     */
    public function isValidTag($tag) {

        if (strlen($tag) > 3) {
            return false;
        }

        if (is_numeric($tag)) {
            return false;
        }

        if (count(explode(" ", $tag)) !== 1) {
            return false;
        }

        if (str_contains($tag, array(".com", ".net", ".es", ".xxx", ".tv", ".co", "-"))) {
            return false;
        }

        return true;
    }
}