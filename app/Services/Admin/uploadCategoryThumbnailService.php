<?php

namespace App\Services\Admin;

use App\Model\Category;
use Illuminate\Support\Facades\Request;

class uploadCategoryThumbnailService
{
    public function execute($category_id)
    {
        try {
            $category = Category::find($category_id);

            if (!$category){
                return [ 'status' => false, 'message' => "Category $category_id not found" ];
            }

            $fileName = md5(microtime() . $category_id) . ".jpg";
            $final_url = "http://" . $category->site->getHost() . "/categories_custom/" . $fileName;
            $destinationPath = public_path()."/categories_custom/";

            // lock category thumbnail
            foreach($category->translations()->get() as $translation) {
                $translation->thumb_locked = 1;
                $translation->thumb = $final_url;
                $translation->save();
            }

            Request::file('file')->move($destinationPath, $fileName);

            return [
                "status" => true,
                "files"  => [
                    [
                        "category_id" => $category_id,
                        "name"        => $fileName,
                        "url"         => $final_url,
                    ]
                ]
            ];

        } catch (\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}