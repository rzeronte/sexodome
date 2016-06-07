<?php

namespace App\Console\Commands;

use Faker\Provider\tr_TR\DateTime;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Alaouy\Youtube\Facades\Youtube;
use App\Model\Category;
use App\Model\Scene;
use App\Model\SceneTranslation;
use App\Model\Language;
use Illuminate\Support\Facades\DB;
use App\Model\TagTranslation;
use App\Model\Tag;
use App\rZeBot\rZeBotUtils;
use App\Model\SceneTag;

class updateThumbnailsCategories extends Command
{
    protected $signature = 'rZeBot:categories:thumbnail {site_id';

    protected $description = 'Actualiza las thumbs de las categorías';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("Error el site id: $site_id no existe");
            exit;
        }

        $categories = Category::all();

        foreach($categories as $category) {
            foreach ($category->translations()->get() as $translation) {
                $this->info("Actualizando thumbnail para la categoría $category->id, Lang: $translation->language_id: $translation->name");
                $translation->thumb = $category->scenes()->orderByRaw("RAND()")->first()->preview;
                $translation->save();
            }
        }
    }
}