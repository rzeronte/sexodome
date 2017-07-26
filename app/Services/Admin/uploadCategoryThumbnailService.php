<?php

namespace DDD\Application\Service\Admin;

class uploadCategoryThumbnailService
{
    public function execute($category_id, Request $request)
    {
        try {
            $category = Category::findOrFail($category_id);

            // logo validator
            /*        $v = Validator::make($request->all(), [
                        'file' => 'required|mimes:jpg,jpeg',      // max=50*1024; min=3*1024
                    ]);

                    if ($v->fails()) {
                        $data = ["error" => "Upload invalid file. Check your file, size ane extension (JPG only)!"];

                        return json_encode($data);
                    }
            */
            $fileName = md5(microtime() . $category_id) . ".jpg";

            $final_url = "http://" . $category->site->getHost() . "/categories_custom/" . $fileName;

            $destinationPath = public_path()."/categories_custom/";

            // lock category thumbnail
            foreach($category->translations()->get() as $translation) {
                $translation->thumb_locked = 1;
                $translation->thumb = $final_url;
                $translation->save();
            }

            $request->file('file')->move($destinationPath, $fileName);

            $data = [
                "status" => true,
                "files"  => [
                    [
                        "category_id" => $category_id,
                        "name"        => $fileName,
                        "url"         => $final_url,
                    ]
                ]
            ];

            return json_encode($data);
        } catch (\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}