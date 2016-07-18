<?php

namespace App\Console\Commands;

use Faker\Provider\tr_TR\DateTime;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Alaouy\Youtube\Facades\Youtube;
use TubeFront\Model\Category;
use TubeFront\Model\CategoryTranslation;
use TubeFront\Model\Scene;
use TubeFront\Model\SceneTranslation;
use TubeFront\Model\Language;
use Illuminate\Support\Facades\DB;
use TubeFront\Model\TagTranslation;
use TubeFront\Model\Tag;
use TubeFront\Bot\rZeBotUtils;
use TubeFront\Model\SceneTag;
use TubeFront\Model\SceneCategory;

class rZeBotCreateCategoriesFromTags extends Command
{
    protected $signature = 'rZeBot:create:categories
                    {--truncate=false : Determine if truncate tables}
                    {--SCENES_MIN=250: Determine if truncate tables}';


    protected $description = 'Create categories from tags';

    public function handle()
    {
        $truncate = $this->option("truncate");
        $SCENES_MIN = $this->option("SCENES_MIN");

        if ($truncate !== "false") {
            $this->info("Truncamos tablas");
            DB::table('categories_translations')->delete();
            DB::table('categories')->delete();
            DB::table('scene_category')->delete();
        }

        $english_id = 2;

        $languages = Language::all();

        $tags = Tag::getTranslationSearch("", $english_id);

        $this->info("Escaneando tags (".$tags->count().")");
        foreach($tags->get() as $tag) {

            if (count(explode(" ", $tag->name)) == 1) {
                echo "Procesando tag: " . $tag->name;

                $countScenes = $tag->scenes()->count();

                if ($countScenes >= $SCENES_MIN) {

                    $singular = str_singular($tag->name);
                    $plural = str_plural($tag->name);

                    echo " | $countScenes >= SCENES_MIN | ($singular-$plural)";

                    if ($tag->name == $plural) {
                        echo " -> Plural";
                    }

                    if ($tag->name == $singular) {
                        echo " -> Singular";
                    }

                    $categoryTranslation = CategoryTranslation::where("name", "=", $plural)->where("language_id", "=", $english_id)->first();

                    if (!$categoryTranslation) {
                        // create category
                        $newCategory = new Category();
                        $newCategory->text = $tag->name;
                        $newCategory->status = 1;
                        $newCategory->save();

                        // create category languages
                        foreach($languages as $language) {
                            $newCategoryTranslation = new CategoryTranslation();
                            $newCategoryTranslation->category_id = $newCategory->id;
                            $newCategoryTranslation->language_id = $language->id;
                            $newCategoryTranslation->thumb = $tag->scenes()->orderByRaw("RAND()")->first()->preview;
                            if ($language->id == $english_id) {
                                $newCategoryTranslation->permalink = str_slug($plural);
                                $newCategoryTranslation->name = str_slug($plural);
                            }
                            echo " | ($language->code) | ";
                            $newCategoryTranslation->save();
                        }

                        echo PHP_EOL;

                        // sync scenes to category
                        $ids_sync = [];
                        foreach($tag->scenes()->select("scenes.id")->get() as $video) {
                            $ids_sync[] = $video->id;
                        }
                        $this->info("[CREATE] Creando categoría $plural y sincronizando para $countScenes escenas");
                        $newCategory->scenes()->sync($ids_sync);

                    } else {
                        $plural = str_plural($tag->name);

                        echo PHP_EOL;

                        $this->info("[WARNING] La categoría: " . $plural. " ya existe, sincronizamos $countScenes escenas y seguimos...");
                        $ids_sync = [];

                        $category = Category::find($categoryTranslation->category_id);

                        foreach($tag->scenes()->select("scenes.id")->get() as $video) {
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
                }
                echo PHP_EOL;

            }
        }
    }
}