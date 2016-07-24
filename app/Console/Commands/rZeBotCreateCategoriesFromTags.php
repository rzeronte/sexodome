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
use App\Model\SceneCategory;
use App\Model\Site;

class rZeBotCreateCategoriesFromTags extends Command
{
    protected $signature = 'rZeBot:create:categories {site_id}
                    {--truncate=false : Determine if truncate tables}
                    {--min_scenes_activation=250: Determine if active category}';


    protected $description = 'Create categories from tags for a site';

    public function handle()
    {
        $site_id = $this->argument("site_id");
        $min_scenes_activation = $this->argument("min_scenes_activation");

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message('[ERROR]: El site_id: '. $site_id . " no existe", "red");
            die();
        }

        $truncate = $this->option("truncate");

        if ($truncate !== "false") {
            $this->info("Truncamos tablas");
            DB::table('categories')->where("site_id", $site_id)->delete();
        }

        // obtenemos todos los tags en inglés, que es el idioma referencia
        // ya que los dumps originales son en inglés.
        $englishLanguage = Language::where('code', 'en')->first();
        $tags = Tag::getTranslationSearch(false, $englishLanguage->id, $site_id);

        $languages = Language::all();

        $this->info("Escaneando para ". $tags->count() ." tags para el site ". $site->getHost());

        foreach($tags->get() as $tag) {

            // Solo se convertirán en categorías tags de una sola palabra
            if (count(explode(" ", $tag->name)) == 1) {
                echo "Procesando tag: " . $tag->name;

                // Contamos el ńumero de escenas para este tags
                $countScenes = $tag->scenes()->where('status', 1)->count();

                // Si existe un umbral de escenas suficiente, el tag es una potencial categoría
                if ($this->isValidTag($tag->name)) {

                    $singular = str_singular($tag->name);
                    $plural = str_plural($tag->name);

                    echo " | $countScenes >= SCENES_MIN | ($singular-$plural)";

                    // Debug en pantalla para ver si el el tag es singular o plural
                    if ($tag->name == $plural) {
                        echo " -> Plural";
                    } else if ($tag->name == $singular) {
                        echo " -> Singular";
                    }

                    // Comprobamos si ya existe el tag en plural (las categorías solo serán plural)
                    $categoryTranslation = CategoryTranslation::where("name", "=", $plural)
                        ->where("language_id", "=", $englishLanguage->id)
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
                            $newCategoryTranslation->thumb = $tag->scenes()->orderByRaw("RAND()")->limit(100)->first()->preview;

                            if ($language->id == $englishLanguage->id) {
                                $newCategoryTranslation->permalink = str_slug($plural);
                                $newCategoryTranslation->name = str_slug($plural);
                            }
                            echo " | ($language->code) | ";
                            $newCategoryTranslation->save();
                        }

                        echo PHP_EOL;

                        // sync scenes to category
                        $ids_sync = [];

                        foreach($tag->scenes()->where('status', 1)->select("scenes.id")->get() as $video) {
                            $ids_sync[] = $video->id;
                        }

                        $this->info("[CREATE] Creando categoría $plural en http://".$site->getHost()." y sincronizando para $countScenes escenas");
                        $newCategory->scenes()->sync($ids_sync);

                    } else {
                        $plural = str_plural($tag->name);

                        echo PHP_EOL;

                        $this->info("[WARNING] La categoría: " . $plural. " ya existe en ".$site->getHost() . ", sincronizamos para $countScenes escenas...");

                        // Obtenemos la categoría partiendo de la traducción
                        $category = Category::find($categoryTranslation->category_id);

                        if ($countScenes >= $min_scenes_activation) {
                            $category->status = 1;
                        } else {
                            $category->status = 0;
                        }

                        foreach($tag->scenes()->where('status', 1)->select("scenes.id")->get() as $video) {
                            try {
                                $sceneCategory = new SceneCategory();
                                $sceneCategory->scene_id = $video->id;
                                $sceneCategory->category_id = $category->id;
                                $sceneCategory->save();

                            } catch(\Exception $e){
                                $this->info("\033[31m[WARNING] La categoría: " . $plural. " ya está asociada al vídeo $video->id...");
                            }
                        }
                    }
                } else {
                    $this->info("\033[31m[WARNING] Ignorando categoría: " . $tag->name);
                }
                echo PHP_EOL;
            }
        }
    }

    public function isValidTag($tag) {

        if (!strlen($tag->name)) {
            return false;
        }

        if (is_numeric($tag->name)) {
            return false;
        }

        return true;
    }
}