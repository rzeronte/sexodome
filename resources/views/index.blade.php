<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('layout_admin._head')

<body style="background-color: dimgray;">

<div class="container">
    <div class="header row">
        @include('layout_admin._header_config')
    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <form action="{{ route('content', ['locale'=>$locale]) }}" method="get" style="width:100%">
            <div class="col-md-4">
                <input id="query_string" name="q" type="text" placeholder="title search" class="form-control query_string" value="{{$query_string}}" style="width:100%;">
            </div>
            <div class="col-md-4">
                    <input id="query_tags" name="tag_q" type="text" placeholder="tag search" class="form-control query_string" value="{{$tag_q}}" style="width:100%;">
            </div>
            <div class="col-md-3">
                <select class="form-control" name="publish_for" style="width:100%">
                    <option value="">all</option>
                    @foreach($sites as $site)
                        @if ($site["name"] == $publish_for)
                            <option value="{{$site['name']}}" selected>{{$site['name']}}</option>
                        @else
                            <option value="{{$site['name']}}">{{$site['name']}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">search</button>
            </div>
        </form>
    </div>

    <div class="row" style="background-color:white;padding:10px;padding-bottom:15px;">
        <div class="col-md-12">
            <p><b>{{ number_format($total_scenes, 0, ",", ".") }}</b> scenes found for:
            @if ($query_string != "")
                    <b><i>"{{$query_string}}"</i></b> in title
            @else
                    <b><i>any title</i></b>
            @endif
            @if ($tag_q != "")
                and <b><i>"{{$tag_q}}"</i></b> tag
            @endif
            </p>
        </div>
    </div>
    <div class="row" style="background-color:white;font-size:12px;">
        <div class="col-md-1 text-center">
            <b>Image</b>
        </div>
        <div class="col-md-3 text-center">
            <b>Title/Description</b>
        </div>
        <div class="col-md-2 text-center">
            <b>Tags</b>
        </div>
        <div class="col-md-2 text-center">
            <b>Sites status</b>
        </div>
        <div class="col-md-4 text-center">
            <b>Publish in</b>
        </div>

    </div>

    <?php $loop = 0 ?>
    @foreach($scenes as $scene)
        <?php
            $thumbs = json_decode($scene->thumbs);
            $index = rand(0, count($thumbs)-1);
            $loop++;

            if ($loop % 2) {
                $bgColor = '#e8e8e8';
            } else {
                $bgColor = 'lightyellow';
            }
        ?>

        <div class="row coloreable" style="background-color:<?=$bgColor?>;">

                <div class="col-md-1">
                    <img title="{{$scene->permalink}}" src="<?=htmlspecialchars($scene->preview)?>" class="img-responsive thumbnail"/>
                    <small><b>{{number_format($scene->rate, 2)}}p. <br/>
                    {{gmdate("i:s", $scene->duration)}}</b></small>
                </div>

                <div class="col-md-4" style="margin: 5px 0 0 0">
                    <form action="{{route('saveTranslation', ['locale'=>$locale, 'scene_id'=>$scene->id])}}" class="ajax-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="text" value="{{$scene->title}}" class="form-control" name="title"/>
                        <textarea class="form-control" style="margin-top:5px;margin-bottom:5px;" name="description">{{$scene->description}}</textarea>
                        <input type="submit" class="btn btn-primary" value="update" style="margin-right:10px;margin-bottom:5px;"/>
                    </form>
                </div>

                <div class="col-md-2" style="margin: 15px 0 0 0">
                    @foreach ($scene->tags()->get() as $tag)
                        <small style="background-color: forestgreen;color:white;margin:2px;padding:1px;">
                                <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                                @foreach($sites as $site)
                                    @if (in_array(strtolower($translation->permalink), $site['tags']))
                                       <img src="{{asset('favicons/favicon-'.$site['name'].'.png')}}"/>
                                   @endif
                                @endforeach
                                <a href="{{route('tags',['locale'=>$language->code, 'q'=>$translation->name])}}" style="color:white;" target="_blank">{{$translation->name}}</a>
                        </small>
                    @endforeach
                </div>

                <div class="col-md-2" style="margin: 10px 0 0 0">
                    <small><b>Available in:</b></small><br/>
                    @foreach ($languages as $itemLang)
                        <?php $translation = $scene->translations()->where('language_id',$itemLang->id)->first(); ?>
                        @if (isset($translation->title))
                            <small>[T] </small><img src="{{asset("flags/$itemLang->code.png")}}"/>
                        @endif
                        @if (isset($translation->description))
                            <small>[D] </small><img src="{{asset("flags/$itemLang->code.png")}}"/>
                        @endif
                        <br/>
                    @endforeach

                    <small><b>Published in:</b></small><br/>
                    @if (count($scene->logspublish()->get()) == 0)
                        <small>NoPublished</small>
                    @endif
                    @foreach ($scene->logspublish()->get() as $publish)
                        <img src="{{asset('favicons/favicon-'.$publish->site.'.png')}}" style="float:left;"/><small style="margin-left:5px;float:left;margin-right: 5px;">{{$publish->site}}</small>
                        @foreach($languages as $itemLang)
                            <?php $translation = DB::connection($publish->site)->table('scene_translations')->where('scene_id', $scene->id)->where('language_id', $itemLang->id)->first();?>
                                @if (isset($translation->title))
                                    <img src="{{asset("flags/$itemLang->code.png")}}"/>
                                @endif
                        @endforeach
                        <br/>
                    @endforeach
                </div>
            <form action="{{route('exportScene', ['locale'=>$locale, 'scene_id'=>$scene->id, 'q' => Request::get("q"),'tag_q' => Request::get("tag_q"),  'page' => Request::get("page")])}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="col-md-2" style="margin: 15px 0 0 0">
                    <select class="form-control" name="database" style="width:100%">
                        @foreach($sites as $site)
                            <option value="{{$site['name']}}">{{$site['name']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1" style="margin: 15px 0 0 0">
                    <input type="submit" value="export" class="btn btn-primary form-control"/>
                </div>
            </form>
        </div>

    @endforeach

    <div class="row">
        <?php echo $scenes->appends(['locale'=>$locale, 'q' => $query_string, 'tag_q' => $tag_q, 'publish_for' => $publish_for])->render(); ?>
    </div>

</div>
<style>
    .successAjax{
        border: solid 3px green;
    }

    .errorAjax{
        border: solid 3px red;
    }

</style>
</body>
</html>
