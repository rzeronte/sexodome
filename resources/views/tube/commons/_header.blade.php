<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
                <a class="navbar-brand" href="{{route('categories', ['profile' => $profile])}}" title="{{$site->getHost()}}" style="margin: 0;padding:0;">
                    <h1 style="margin-top:10px;">
                    @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                        <img src="{{asset('/logos/'.md5($site->id).".png")}}" alt="{{$site->getHost()}}" title="{{str_replace('.com', '', $site->domain)}}"/>
                        @if ($site->have_domain == 1)
                            <span style="position: absolute; left:-1000px;">{{str_replace('.com', '', $site->domain)}}</span>
                        @else
                            <span style="position: absolute; left:-1000px;">{{$site->getHost()}}</span>
                        @endif
                    @else
                        @if ($site->have_domain == 1)
                            {{$site->domain}}
                        @else
                            {{$site->getHost()}}
                        @endif
                    @endif
                    </h1>
                </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="navbar navbar-nav list-inline" style="background-color: transparent !important;">
                <li>
                    <p class="navbar-btn">
                        <a href="{{route('categories', ['profile' => $profile])}}" class="btn btn-default">{{ucwords(trans('tube.h1_info_categories'))}}</a>
                    </p>
                </li>

                @if ($site->button1_url != "" and $site->button1_text != "")
                    <li>
                        <p class="navbar-btn">
                            <a href="{{$site->button1_url}}" target="_blank" class="btn btn-default">{{$site->button1_text}}</a>
                        </p>
                    </li>
                @endif
                @if ($site->button2_url != "" and $site->button2_text != "")
                    <li>
                        <p class="navbar-btn">
                            <a href="{{$site->button2_url}}" class="btn btn-default">{{$site->button2_text}}</a>
                        </p>
                    </li>
                @endif
                @if ($site->pornstars()->count() > 0)
                <li>
                    <p class="navbar-btn">
                        <a href="{{route('pornstars', ["profile" => $profile])}}" class="btn btn-default btn-header-pornstars">{{trans('tube.header_pornstars_btn')}}</a>
                    </p>
                </li>
                @endif

            </ul>
            <form action="{{ route('search', ['profile' => $profile]) }}" method="get" class="navbar-form navbar-left">
                <div class="form-group">
                    <input name="q" type="text" placeholder="{{trans('tube.header_inputsearch_placeholder')}}" class="form-control" value="{{$query_string}}" required>
                </div>
                <button type="submit" class="btn btn-default"><i class="small mdi mdi-search"></i> {{trans('tube.header_inputsearch_search')}}</button>
            </form>
            <span class="navbar-text pull-right">
          <h4>
              <?php if (isset($categoryTranslation)):?>
              {{ ucwords($categoryTranslation->name) }} {{ucwords(trans('tube.h1_info_porn_videos'))}}
              <?php else:?>
              {{$site->head_billboard}}
              <?php endif?>
          </h4>
        </span>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


<aside class="billboard">
    <div class="container text-center">
        @if (strlen($site->link_billboard) > 0)
            <?php echo $site->link_billboard ?>
        @endif
    </div>
</aside>

<script type="text/javascript">
    <?php
    $popunders = Cache::remember('popunders_'.$site->id, env('MEMCACHED_QUERY_TIME', 30), function() use ($site) {
        return $site->popunders()->get();
    });
    ?>
    var popunders = new Array();
    @foreach( $popunders as $popunder)
    popunders.push('{{$popunder->url}}');
    @endforeach
</script>
