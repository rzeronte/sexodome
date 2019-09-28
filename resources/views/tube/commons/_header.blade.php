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
                <a class="navbar-brand" href="{{route('categories', ['profile' => App::make('sexodomeKernel')->getSite()->domain ])}}" title="{{ App::make('sexodomeKernel')->getSite()->domain }}" style="margin: 0;padding:0;">
                    <h1>
                    @if (file_exists(\App\rZeBot\sexodomeKernel::getLogosFolder()."/".md5(App::make('sexodomeKernel')->getSite()->id).".png"))
                        <img src="{{asset('/logos/'.md5(App::make('sexodomeKernel')->getSite()->id).".png")}}" alt="{{App::make('sexodomeKernel')->getSite()->logo_h1}}" title="{{ App::make('sexodomeKernel')->getSite()->logo_h1}}"/>
                        <span style="position: absolute; left:-1000px;">{{App::make('sexodomeKernel')->getSite()->logo_h1}}</span>
                    @else
                        {{trim(App::make('sexodomeKernel')->getSite()->logo_h1)}}
                    @endif
                    </h1>
                </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="navbar navbar-nav list-inline" style="background-color: transparent !important;">
                <li>
                    <p class="navbar-btn">
                        <a href="{{route('categories', ['profile' => App::make('sexodomeKernel')->getSite()->domain ])}}" class="btn btn-default">{{ucwords(trans('tube.h1_info_categories'))}}</a>
                    </p>
                </li>

                @if (App::make('sexodomeKernel')->getSite()->button1_url != "" and App::make('sexodomeKernel')->getSite()->button1_text != "")
                    <li>
                        <p class="navbar-btn">
                            <a href="{{App::make('sexodomeKernel')->getSite()->button1_url}}" target="_blank" class="btn btn-default">{{App::make('sexodomeKernel')->getSite()->button1_text}}</a>
                        </p>
                    </li>
                @endif
                @if (App::make('sexodomeKernel')->getSite()->button2_url != "" and App::make('sexodomeKernel')->getSite()->button2_text != "")
                    <li>
                        <p class="navbar-btn">
                            <a href="{{App::make('sexodomeKernel')->getSite()->button2_url}}" class="btn btn-default">{{App::make('sexodomeKernel')->getSite()->button2_text}}</a>
                        </p>
                    </li>
                @endif
                @if (App::make('sexodomeKernel')->getSite()->pornstars()->count() > 0)
                <li>
                    <p class="navbar-btn">
                        <a href="{{route('pornstars', ["profile" => App::make('sexodomeKernel')->getSite()->domain ])}}" class="btn btn-default btn-header-pornstars">{{trans('tube.header_pornstars_btn')}}</a>
                    </p>
                </li>
                @endif

            </ul>
            <form action="{{ route('search', ['profile' => App::make('sexodomeKernel')->getSite()->domain ]) }}" method="get" class="navbar-form navbar-left">
                <div class="form-group">
                    <input name="q" type="text" placeholder="{{trans('tube.header_inputsearch_placeholder')}}" class="form-control" value="{{ Request::input('q') }}" required>
                </div>
                <button type="submit" class="btn btn-default"><i class="small mdi mdi-search"></i> {{trans('tube.header_inputsearch_search')}}</button>
            </form>
            <span class="navbar-text pull-right">
            <p class="right_text">{{App::make('sexodomeKernel')->getSite()->header_text}}</p>

        </span>
        </div>
    </div>
</nav>

@include('tube.commons._link_billboard')

<script type="text/javascript">
    <?php
    $site = $site = App::make('sexodomeKernel')->getSite();
    $popunders = Cache::remember('popunders_'.App::make('sexodomeKernel')->getSite()->id, env('MEMCACHED_QUERY_TIME', 30), function() use ($site) {
        return $site->popunders()->get();
    });
    ?>
    var popunders = new Array();
    @foreach( $popunders as $popunder)
    popunders.push('{{$popunder->url}}');
    @endforeach

</script>
