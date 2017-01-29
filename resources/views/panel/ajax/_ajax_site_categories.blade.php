<?php $loop = 0 ?>
<div class="row">
    @if (count($categories) == 0)
        <div class="row" style="margin:0px;padding:15px;">
            Currently no categories
        </div>
    @endif

    @foreach($categories as $category)
        <?php
        $loop++;

        if ($loop % 2) {
            $bgColor = '#e8e8e8';
        } else {
            $bgColor = 'lightyellow';
        }
        ?>

        <div class="col-md-12 coloreable category-form-{{$category->id}}" style="padding:10px;background-color:<?=$bgColor?>;">
            <div class="row container">

                <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>


                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <?php $srcThumbnail = asset('/thumbnails/'.md5($translation->thumb).".jpg")?>
                            <img src="{{$srcThumbnail}}" class="border-thumb category-preview" style="width:100%; border: solid 1px black;margin-bottom: 10px;"/>

                            <form action="{{route('uploadCategory', ['category_id'=>$category->id])}}" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input class="fileupload" type="file" name="file" data-url="{{ route( 'uploadCategory', [ 'category_id'  => $category->id ] ) }}">
                            </form>

                        </div>
                        <div class="col-md-6 container-lock-action" data-unlock-category-url="{{route('categoryUnlock', ['locale' => $locale, 'category_translation_id' => $translation->id])}}">
                            @if ($translation->thumb_locked == 1)
                                <span class="locked"><i class="glyphicon glyphicon-ban-circle"></i> Thumbnail locked</span>
                                <div class="clearfix"></div>
                                <a href="{{route('categoryUnlock', ['locale' => $locale, 'category_translation_id' => $translation->id])}}" class="btn btn-success btn-xs btn-category-unlock"><i class="glyphicon glyphicon-cog"></i> Unlock</a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button data-toggle="modal" data-target="#modal-sexodome" data-url="{{route('categoryThumbs', ['locale' => $locale, 'category_id' => $category->id])}}" class="btn btn-primary btn-xs btn-change-category-thumbnail" style="margin-top:10px;"><i class="glyphicon glyphicon-picture"></i> Change Thumbnail</button>
                        </div>
                    </div>

                </div>

                <form action="{{route('saveCategoryTranslation', ['locale'=>$locale, 'category_id' => $category->id, 'q'=> Request::input("q"), 'page' => Request::input("page")])}}" method="post" class="ajax-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="thumbnail" value="{{ $translation->thumb }}"/>

                    <div class="col-md-5">
                        <div class="input-group">
                            <input name="language_{{$language->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}">
                            <span id="basic-addon2" class="input-group-addon"><img alt="{{$translation->permalink}}" src="{{asset("flags/$language->code.png")}}"/></span>
                        </div>

                        <div class="clearfix"></div>
                        @if ($language->id != 2)
                            <?php $translationEN = $category->translations()->where('language_id', 2)->first(); ?>
                            <small>Original EN: {{$translationEN->name}} / {{$translationEN->permalink}}</small>
                        @endif
                        <select name="status" class="form-control" style="width:70%;">
                            <option value="0" <?=($category->status == '0')?"selected":""?>>Inactive</option>
                            <option value="1" <?=($category->status == '1')?"selected":""?>>Active</option>
                        </select>

                        <div class="clearfix"></div>

                    </div>



                    <div class="col-md-1">
                        <input type="submit" class="btn btn-primary" value="Update"/>
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
