<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\File;
use App\Model\Category;

class showCategoryThumbsService
{
    public function execute($category_id, $sex_types)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return [ 'status' => false, 'message' => "Category $category_id not found"];
        }

        $site_type_id = $category->site->type_id;

        if ($site_type_id == $sex_types['straigth']) {
            $files = File::allFiles(public_path()."/categories_market");
        } else {
            $files = File::allFiles(public_path()."/categories_market_gay");
        }

        $filenames = [];
        foreach ($files as $file) {
            $filenames[] = $file->getFilename();
        }

        return [
            'category'  => $category,
            'filenames' => $filenames,
        ];
    }
}