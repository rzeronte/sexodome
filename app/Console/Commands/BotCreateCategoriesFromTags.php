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
            rZeBotUtils::message("Truncamos tablas", "yellow");
            DB::table('categories')->where("site_id", $site_id)->delete();
            if ($only_truncate !== "false") {
                rZeBotUtils::message("[EXIT] only_truncated", "red");
                exit;
            }
        }

        // obtenemos todos los tags en inglés, que es el idioma referencia
        // ya que los dumps originales son en inglés.
        $englishLanguage = Language::where('code', 'en')->first();
        $tags = Tag::getTranslationSearch(false, $englishLanguage->id, $site_id);

        $languages = Language::all();

        rZeBotUtils::message("Escaneando para ". $tags->count() ." tags para el site ". $site->getHost(), "yellow");

        $tags = $tags->get();
        $i = 0;
        $timer = time();
        $abs_total = count($tags);
        foreach($tags->chunk(500) as $chunk) {
            DB::transaction(function () use ($chunk, $site_id, $min_scenes_activation, $englishLanguage, $languages, $tags, &$i,$timer, $abs_total) {
                foreach($chunk as $tag) {
                    rZeBotUtils::createCategoryFromTag(
                        $tag,
                        $site_id,
                        $min_scenes_activation,
                        $languages,
                        $englishLanguage->id,
                        $abs_total,
                        $timer,
                        $i
                    );
                }
            });
        }
    }
}