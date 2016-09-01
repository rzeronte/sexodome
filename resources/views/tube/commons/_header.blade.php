<section class="header">

    <div class="container">
        <div class="col-md-3 col-xs-12 col-sm-12 col-logo">
            <a href="{{route('categories', ['profile' => $profile])}}" title="{{$site->title}}">
                @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                    <img src="{{asset('/logos/'.md5($site->id).".png")}}"/>
                @else
                    @if ($site->have_domain == 1)
                        {{$site->domain}}
                    @else
                        <h1 class="text-center">{{$site->name}}</h1>
                    @endif
                @endif
            </a>
        </div>
        <div class="col-md-1 col-xs-3 col-sm-3 text-center col-header-right">
            <a href="{{route('pornstars', ["profile" => $profile])}}" class="btn btn-header-pornstars">Pornstars</a>
        </div>

        <div class="col-md-4 col-xs-9 col-sm-9">
            <form action="{{ route('search', ['profile' => $profile]) }}" method="get">
                <div class="input-group input-search">
                    <input name="q" type="text" placeholder="what you wanna watch?" class="form-control" value="{{$query_string}}" required>
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> search</button>
                    </span>
                </div>
            </form>
        </div>

        <div class="col-md-4 col-xs-12 col-sm-12">
            <h1 class="text-right billboard"><?php if (isset($tagTranslation)):?>{{$tagTranslation->name}} | <?php endif?>{{$site->head_billboard}}</h1>
        </div>
    </div>
</section>

<script>
    var popunders = new Array();
    @foreach($site->popunders()->get() as $popunder)
        popunders.push('{{$popunder->url}}');
    @endforeach
</script>