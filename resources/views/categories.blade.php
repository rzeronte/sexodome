<!DOCTYPE html>
<html>

@include('layout_admin._head')

<body style="background-color: dimgray;">
<div class="container">
    <div class="header row">
        @include('layout_admin._header_config')
    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <div class="col-md-3">
            <form action="{{ route('categories', ['locale'=>$locale]) }}" method="get" style="width:100%;">

                <div class="input-group">
                    <input id="query_string" name="q" type="text" placeholder="" class="form-control query_string" value="{{$query_string}}">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Find</button>
                </span>
                </div>
            </form>
        </div>
        <div class="col-md-9" style="text-align:right;">
        </div>
    </div>

    <?php $loop = 0 ?>
    @foreach($categories as $category)
        <?php
        $loop++;

        if ($loop % 2) {
            $bgColor = '#e8e8e8';
        } else {
            $bgColor = 'lightyellow';
        }
        ?>

        <div class="row coloreable" style="padding:10px;background-color:<?=$bgColor?>;">
            <form action="{{route('saveCategoryTranslation', ['locale'=>$locale, 'category_id' => $category->id, 'q'=> Request::input("q"), 'page' => Request::input("page")])}}" method="post" class="ajax-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="col-md-1">
                    <input type="submit"class="btn btn-primary" value="Update"/>
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

                @foreach ($languages as $lang)
                    <?php $translation = $category->translations()->where('language_id',$lang->id)->first(); ?>

                    <div class="col-md-2">
                        <div class="input-group">
                            @if ($lang->id == $language->id)
                                <input name="language_{{$lang->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}">
                            @else
                                <input name="language_{{$lang->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}" disabled>
                            @endif
                            <span id="basic-addon2" class="input-group-addon"><img alt="{{$translation->permalink}}" src="{{asset("flags/$lang->code.png")}}"/></span>
                        </div>
                    </div>
                @endforeach

            </form>
        </div>
        <div class="row" style="padding:10px;background-color:<?=$bgColor?>;">

        </div>

    @endforeach

    <div class="row" style="background-color:white;padding:10px;">
        <?php echo $categories->appends(['q' => $query_string])->render(); ?>
    </div>

</div>
</body>
</html>
