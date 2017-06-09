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
                <?php $translation = $tag->translations()->where('language_id', $site->language->id)->first(); ?>

                <div class="col-md-6">
                    <div class="input-group">
                        <input name="language_{{$site->language->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}">
                        <span id="basic-addon2" class="input-group-addon"><img alt="{{$translation->permalink}}" src="{{asset("flags/".$site->language->code.".png")}}"/></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <input type="submit"class="btn btn-primary btn-xs" value="Update"/>
                    <a href="{{route('ajaxDeleteTag', ['tag_id' => $tag->id])}}" class="btn btn-danger btn-xs btn-delete-tag"><i class="glyphicon glyphicon-trash"></i> Remove</a>
                </div>

            </form>
        </div>

    @endforeach

</div>

<div class="row site_tags_paginator" style="padding:10px;">
    <?php $tags->setPath('tags/'.$site->id);?>
    <?php echo $tags->render(); ?>
</div>
