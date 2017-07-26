<?php

namespace DDD\Application\Service\Admin;

class showCategoryThumbsService
{
    public function execute($category_id)
    {
        $category = Category::findOrFail($category_id);

        $site_type_id = $category->site->type_id;

        if ($site_type_id == App::make('sexodomeKernel')->sex_types['straigth']) {
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