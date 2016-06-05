<!DOCTYPE html>
<html>

@include('panel._head')

<body>
<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row" style="margin-top:20px;">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><b>Tags for http://{{$site->domain}}</b></p>
        </div>
    </div>

    <div class="row" style="padding:10px;">

        <div class="col-md-3">
            <form action="{{ route('tags_admin', ['site_id' => $site->id, 'locale'=>$locale]) }}" method="get" style="width:100%;">

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

    <div class="row">
        @foreach($tags as $tag)
            <?php
            $loop++;

            if ($loop % 2) {
                $bgColor = '#e8e8e8';
            } else {
                $bgColor = 'lightyellow';
            }
            ?>

            <div class="col-md-4 coloreable" style="padding:10px;background-color:<?=$bgColor?>;">
                <form action="{{route('saveTagTranslation', ['locale'=>$locale, 'tag_id' => $tag->id, 'q'=> Request::input("q"), 'page' => Request::input("page")])}}" method="post" class="ajax-form">
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

                    <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>

                    <div class="col-md-5">
                        <div class="input-group">
                            <input name="language_{{$language->id}}" type="text" aria-describedby="basic-addon2" placeholder="" class="form-control" value="{{ $translation->name }}">
                            <span id="basic-addon2" class="input-group-addon"><img alt="{{$translation->permalink}}" src="{{asset("flags/$language->code.png")}}"/></span>
                        </div>
                    </div>

                </form>
            </div>

        @endforeach

    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <?php echo $tags->appends(['q' => $query_string])->render(); ?>
    </div>

    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>
</body>
</html>
