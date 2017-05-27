<?php $loop = 0 ?>
<div class="row">
    @if (count($tags) == 0)
        <div class="row" style="margin:0px;padding:15px;">
            Currently no tags
        </div>
    @endif

    @foreach($tags as $tag)
        <div class="col-md-4 coloreable" style="padding:10px;">
            <form action="{{route('saveTagTranslation', ['tag_id' => $tag->id, 'q'=> Request::input("q"), 'page' => Request::input("page")])}}" method="post" class="ajax-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="col-md-3">
                    <input type="submit"class="btn btn-primary" value="Update"/>
                </div>

                <div class="col-md-4">
                    @if ($tag->status == 0)
                        <i class="fa fa-thumbs-down" style="color:red;float:left;margin-right:10px;margin-top:12px;"></i>
                    @else
                        <i class="fa fa-thumbs-up" style="color:green;float:left;margin-right:10px;margin-top:12px;"></i>
                    @endif

                    <select name="status" class="form-control" style="width:70%;">
                        <option value="0" <?=($tag->status == '0')?"selected":""?>>KO</option>
                        <option value="1" <?=($tag->status == '1')?"selected":""?>>OK</option>
                    </select>
                </div>

                <?php $translation = $tag->translations()->where('language_id',App::make('sexodomeKernel')->getLanguage()->id)->first(); ?>

                <div class="col-md-5">
                    <div class="input-group">
                        <input name="language_{{App::make('sexodomeKernel')->getLanguage()->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}">
                        <span id="basic-addon2" class="input-group-addon"><img alt="{{$translation->permalink}}" src="{{asset("flags/".App::make('sexodomeKernel')->getLanguage()->code.".png")}}"/></span>
                    </div>
                </div>

            </form>
        </div>

    @endforeach

</div>

<div class="row site_tags_paginator" style="padding:10px;">
    <?php $tags->setPath('tags/'.$site->id);?>
    <?php echo $tags->render(); ?>
</div>
