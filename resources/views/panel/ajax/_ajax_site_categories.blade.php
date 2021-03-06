<?php $loop = 0 ?>

<div class="row site_categories_paginator" style="padding:10px;">
    <?php $categories->setPath('categories/'.$site->id);?>
    <?php echo $categories->appends('q', Request::get('q'))->render() ?>
</div>

<div class="row coloreable">
    @if (count($categories) == 0)
        <div class="row" style="margin:0px;padding:15px;">
            No categories founded for this search.
        </div>
    @endif

    @foreach($categories as $category)

        <div class="col-md-12 category-form-{{$category->id}} alternate_coloreable" style="padding:10px;border-bottom: solid 1px lightgray;">
            <div class="row container">

                <?php $translation = $category->translation($site->language->id); ?>

                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6" style="min-height:190px;">

                            @if ($translation->thumb_locked == 1)
                                <?php $srcThumbnail = $translation->thumb?>
                            @else
                                <?php
                                    $file_thumbnail = public_path() . '/thumbnails/'.md5($translation->thumb).".jpg";
                                    if (!file_exists($file_thumbnail)) {
                                        $srcThumbnail = asset('/images/image_not_found.png');
                                    } else {
                                        $srcThumbnail = asset('/thumbnails/'.md5($translation->thumb).".jpg");
                                    }
                                ?>
                            @endif

                            <img src="{{$srcThumbnail}}" class="border-thumb category-preview" style="width:100%; border: solid 1px black;margin-bottom: 10px;"/>
                            <div class="container-lock-action" data-unlock-category-url="{{route('categoryUnlock', ['category_translation_id' => $translation->id])}}">
                                @if ($translation->thumb_locked == 1)
                                    <a href="{{route('categoryUnlock', ['category_translation_id' => $translation->id])}}" class="btn btn-success btn-xs btn-category-unlock"><i class="glyphicon glyphicon-cog"></i> Unlock</a>
                                @endif
                            </div>

                        </div>

                        <div class="col-md-3">
                            <button data-toggle="modal" data-target="#modal-sexodome" data-url="{{route('categoryTags', ['category_id' => $category->id])}}" class="btn btn-primary btn-change-category-tags" style="margin-top:10px; width:160px;"><i class="glyphicon glyphicon-th"></i> Change tags</button>
                            <button data-toggle="modal" data-target="#modal-sexodome" data-url="{{route('categoryThumbs', ['category_id' => $category->id])}}" class="btn btn-primary btn-change-category-thumbnail" style="width:160px; margin-top:10px;"><i class="glyphicon glyphicon-picture"></i> Change Thumbnail</button>
                            <form style="margin-top:10px;" action="{{route('uploadCategoryThumbnail', ['category_id'=>$category->id])}}" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-floppy-disk"></i>
                                    <span>Thumbnail Upload</span>
                                    <input class="fileupload" type="file" name="file" data-url="{{ route( 'uploadCategoryThumbnail', [ 'category_id'  => $category->id ] ) }}">
                                </span>
                            </form>

                        </div>
                    </div>
                </div>

                <form action="{{route('saveCategoryTranslation', ['category_id' => $category->id, 'q'=> Request::input("q"), 'page' => Request::input("page")])}}" method="post" class="ajax-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="thumbnail" value="{{ $translation->thumb }}"/>

                    <div class="col-md-4">
                        <div class="input-group">
                            <input name="language_{{$site->language->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}">
                            <span id="basic-addon2" class="input-group-addon"><img alt="{{$translation->permalink}}" src="{{asset("flags/".$site->language->code.".png")}}"/></span>
                        </div>

                        <div class="clearfix"></div>
                        @if ($site->language->id != 2)
                            <?php $translationEN = $category->translations()->where('language_id', 2)->first(); ?>
                            <small><b>Original EN:</b> {{$translationEN->name}} / {{$translationEN->permalink}}</small>
                            <br/>
                            <small><b>Nº Scenes:</b> {{$category->nscenes}}</small>
                            <br/>
                        @endif
                        <select name="status" class="form-control" style="width:70%;">
                            <option value="0" <?=($category->status == '0')?"selected":""?>>Inactive</option>
                            <option value="1" <?=($category->status == '1')?"selected":""?>>Active</option>
                        </select>

                        <div class="clearfix"></div>

                    </div>



                    <div class="col-md-3">
                        <input type="submit" class="btn btn-primary" value="Update"/>
                        <a href="{{route('ajaxDeleteCategory', ['category_id' => $category->id])}}" class="btn btn-danger btn-delete-category"><i class="glyphicon glyphicon-trash"></i> Remove</a>
                    </div>

                </form>

            </div>
        </div>

    @endforeach
</div>

<div class="row site_categories_paginator" style="padding:10px;">
    <?php $categories->setPath('categories/'.$site->id);?>
    <?php echo $categories->appends('q', Request::get('q'))->render() ?>
</div>
