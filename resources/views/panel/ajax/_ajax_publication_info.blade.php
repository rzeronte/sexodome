{{$scene->translations()->where('language_id', App::make('sexodomeKernel')->getLanguage()->id)->first()->title}}
@foreach ($scene->logspublish()->get() as $publish)
    <hr/>
    <img src="{{asset('favicons/favicon-'.$publish->site.'.png')}}" style="float:left;"/><small style="margin-left:5px;float:left;margin-right: 5px;">{{$publish->site}}</small>
    <?php $remote_scene = DB::connection($publish->site)->table('scenes')->where('id', $scene->id)->first();?>
    (<small>{{$remote_scene->published_at}}</small>)
    <br/>
    @foreach(App::make('sexodomeKernel')->getLanguages() as $itemLang)
        <?php $translation = DB::connection($publish->site)->table('scene_translations')->where('scene_id', $scene->id)->where('language_id', $itemLang->id)->first();?>
        <img src="{{asset("flags/$itemLang->code.png")}}"/>
        @if (isset($translation->title))
            @if (strlen($translation->title))
                <small>[T] </small>
            @endif
        @endif
        @if (isset($translation->description))
            @if (isset($translation->title))
                <small>[D] </small>
            @endif
        @endif
    @endforeach
@endforeach
