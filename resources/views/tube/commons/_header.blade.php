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

                    <h1 class="header_logo">
                        <a class="navbar-brand" href="{{route('categories', ['profile' => $profile])}}" title="{{$site->getHost()}}">
                            @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                                <img src="{{asset('/logos/'.md5($site->id).".png")}}" alt="{{$site->getHost()}}" title="{{$site->getHost()}}"/>
                                @if ($site->have_domain == 1)
                                    <span>{{$site->domain}}</span>
                                @else
                                    <span>{{$site->getHost()}}</span>
                                @endif

                            @else
                                @if ($site->have_domain == 1)
                                    {{$site->domain}}
                                @else
                                    {{$site->getHost()}}
                                @endif
                            @endif
                        </a>
                    </h1>

                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="col-sm-2 col-md-4" style="margin-top: 20px;">
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

                    <h2 class="text-right billboard">
                        <?php if (isset($categoryTranslation)):?>
                            {{ ucwords($categoryTranslation->name) }} {{ucwords(trans('tube.h1_info_porn_videos'))}}
                            <?php else:?>
                                {{$site->head_billboard}}
                            <?php endif?>
                    </h2>

                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </div>

</section>

<section style="margin-bottom:10px;text-align: center; margin:5px;">
    <div class="container">
        @if (strlen($site->link_billboard) > 0)
            <div class="alert-warning"><?php echo $site->link_billboard ?></div>
        @endif
    </div>
</section>

<script type="text/javascript">
    var popunders = new Array();
    @foreach($site->popunders()->get() as $popunder)
    popunders.push('{{$popunder->url}}');
    @endforeach
</script>
