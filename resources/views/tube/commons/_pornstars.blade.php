<main class="main">
    <div class="container">
        <header class="page-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @yield('h2_tag')
                    <div>
                        {{number_format($pornstars->total(), 0, ",", ".")}} pornstars
                    </div>

                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                </div>
            </div>
        </header>
        <div class="row">
            @foreach($pornstars as $pornstar)
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                    <div class="thumbnail">
                        <figure>
                            <a href="{{route('pornstar', ['profile' => Route::current()->parameter('host'), 'permalinkPornstar'=>str_slug($pornstar->name)])}}" _target="_blank">
                                <span class="thumb-image">
                                <span class="floater-b-c">{{ str_limit(ucfirst($pornstar->name), $limit = 25 , $end = '...') }}</span>
                                <img class="img" src="{{$pornstar->thumbnail}}" alt="{{ucwords($pornstar->name)}}" style="height:100%;">
                                </span>
                                </a>
                        </figure>
                    </div>
                </div>
            @endforeach

        </div>

        @yield('paginator')

    </div>

</main>