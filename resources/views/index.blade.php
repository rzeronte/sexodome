<!DOCTYPE html>
<html>

@include('layout_admin._head')

<body style="background-color: dimgray;">
<div class="container">
    <div class="header row">
        @include('layout_admin._header_config')
    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <div class="col-md-4">
            <form action="{{ route('content', ['locale'=>$locale]) }}" method="get" style="width:100%">

                <div class="input-group">
                    <input id="query_string" name="q" type="text" placeholder="" class="form-control query_string" value="{{$query_string}}" style="width:100%;">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">Find</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-md-8" style="text-align:right;">
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

        <div class="row" style="background-color:<?=$bgColor?>;">
            <form action="{{route('exportScene', ['locale'=>$locale, 'scene_id'=>$scene->id])}}">
                <div class="col-md-1">
                    <img src="<?=htmlspecialchars($scene->preview)?>" class="img-responsive thumbnail"/>
                </div>

                <div class="col-md-2" style="margin: 5px 0 0 0">
                    <small>{{$scene->title}}</small> (<small><b>{{number_format($scene->rate, 2)}}</b></small>)
                </div>

                <div class="col-md-2" style="margin: 15px 0 0 0">
                    @foreach ($scene->tags()->get() as $tag)
                        <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                        <small>{{$translation->name}} |</small>
                    @endforeach
                </div>

                <div class="col-md-1" style="margin: 5px 0 0 0">
                    @foreach ($languages as $itemLang)
                        <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                        @if ($translation->name != "")
                                <img src="{{asset("flags/$itemLang->code.png")}}"/>
                        @endif
                    @endforeach

                </div>

                <div class="col-md-2" style="margin: 15px 0 0 0">
                    @if (count($scene->logspublish()->get()) == 0)
                        <small>NoPublished</small>
                    @endif
                    @foreach ($scene->logspublish()->get() as $publish)
                        <img src="{{asset('favicons/favicon-'.$publish->site.'.png')}}" style="float:left;"/><small style="margin-left:5px;float:left;clear:right;">{{$publish->site}}</small><br/>
                    @endforeach
                </div>

                <div class="col-md-2" style="margin: 15px 0 0 0">
                    <select class="form-control" name="database" style="width:100%">
                        <option value="assassinsporn">assassinsporn</option>
                        <option value="mamasfollando">mamasfollando</option>
                        <option value="latinasparadise">latinasparadise</option>
                        <option value="dirtyblow">dirtyblow</option>
                    </select>
                </div>

                <div class="col-md-1" style="margin: 15px 0 0 0">
                    <input type="submit" value="export" class="btn btn-primary form-control"/>
                </div>
            </form>
        </div>

    @endforeach

    <div class="row">
        <?php echo $scenes->appends(['locale'=>$locale, 'q' => $query_string])->render(); ?>
    </div>

</div>
</body>
</html>
