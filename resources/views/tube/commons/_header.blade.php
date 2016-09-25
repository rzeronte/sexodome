<section class="header">
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="{{route('categories', ['profile' => $profile])}}" title="{{$site->title}}">
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
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="col-sm-2 col-md-3" style="margin-top: 20px;">
                        @if ($site->button1_url != "" and $site->button1_text != "")
                            <a href="{{$site->button1_url}}" target="_blank" class="btn btn-custom-user">{{$site->button1_text}}</a>
                        @endif

                        @if ($site->button2_url != "" and $site->button2_text != "")
                            <a href="{{$site->button2_url}}" class="btn btn-custom-user">{{$site->button2_text}}</a>
                        @endif

                        <a href="{{route('pornstars', ["profile" => $profile])}}" class="btn btn-header-pornstars">{{trans('tube.header_pornstars_btn')}}</a>
                    </div>
                    <div class="col-sm-3 col-md-3">

                        <form action="{{ route('search', ['profile' => $profile]) }}" method="get" class="navbar-form" style="border: none !important; margin:0;">
                            <div class="input-group input-search">
                                <input name="q" type="text" placeholder="{{trans('tube.header_inputsearch_placeholder')}}" class="form-control" value="{{$query_string}}" required>

                        <span class="input-group-btn">
                            <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> {{trans('tube.header_inputsearch_search')}}</button>
                        </span>
                            </div>
                        </form>
                    </div>

                    <h1 class="text-right billboard"><?php if (isset($tagTranslation)):?>{{$tagTranslation->name}} | <?php endif?>{{$site->head_billboard}}</h1>

                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </div>

</section>

<script>
    var popunders = new Array();
    @foreach($site->popunders()->get() as $popunder)
        popunders.push('{{$popunder->url}}');
    @endforeach
</script>