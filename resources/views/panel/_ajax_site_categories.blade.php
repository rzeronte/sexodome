<?php $loop = 0 ?>
<div class="row">

    @foreach($categories as $category)
        <?php
        $loop++;

        if ($loop % 2) {
            $bgColor = '#e8e8e8';
        } else {
            $bgColor = 'lightyellow';
        }
        ?>

        <div class="col-md-6 coloreable" style="padding:10px;background-color:<?=$bgColor?>;">
            <div class="row container">
                <form action="{{route('saveCategoryTranslation', ['locale'=>$locale, 'category_id' => $category->id, 'q'=> Request::input("q"), 'page' => Request::input("page")])}}" method="post" class="ajax-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="col-md-3">
                        <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                        <div class="input-group">
                            <input name="language_{{$language->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}">
                            <span id="basic-addon2" class="input-group-addon"><img alt="{{$translation->permalink}}" src="{{asset("flags/$language->code.png")}}"/></span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        @if ($category->status == 0)
                            <i class="fa fa-thumbs-down" style="color:red;float:left;margin-right:10px;margin-top:12px;"></i>
                        @else
                            <i class="fa fa-thumbs-up" style="color:green;float:left;margin-right:10px;margin-top:12px;"></i>
                        @endif

                        <select name="status" class="form-control" style="width:70%;">
                            <option value="0" <?=($category->status == '0')?"selected":""?>>Inactive</option>
                            <option value="1" <?=($category->status == '1')?"selected":""?>>Active</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <input type="submit"class="btn btn-primary" value="Update"/>
                    </div>

                </form>

            </div>
        </div>

    @endforeach
</div>

<div class="row site_categories_paginator" style="background-color:white;padding:10px;">
    <?php $categories->setPath('categories/'.$site->id);?>
    <?php echo $categories->render(); ?>
</div>
