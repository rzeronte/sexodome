<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    public function translations()
    {
        return $this->hasMany('App\Model\CategoryTranslation');
    }

    static function getTranslationSearch($query_string = false, $language_id)
    {
        $categories = Category::select('categories.*', 'categories_translations.name', 'categories_translations.permalink', 'categories_translations.id as translationId')
            ->join('categories_translations', 'categories_translations.category_id', '=', 'categories.id')
            ->where('categories_translations.language_id', $language_id);

        if ($query_string != false) {
            $categories->where('categories_translations.name', 'like', '%'.$query_string.'%');
        }

        return $categories;
    }
}
