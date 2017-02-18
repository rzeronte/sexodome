<main class="main">
    <div class="container">
        <header class="page-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <i class="mdi mdi-stars"></i>
                    <h2>
                        {{number_format($pornstars->total(), 0, ",", ".")}} pornstars
                    </h2>
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
                            <a href="{{route('pornstar', ['profile' => $profile, 'permalinkPornstar'=>str_slug($pornstar->name)])}}" _target="_blank">
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

        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($i = 0; $i < $pornstars->lastPage(); $i++): ?>
                @if (($i+1) == Request::get('page'))
                    <li class="page-item active">
                        <a href="{{route('pornstars', ['host' => $site->domain])}}?page=<?=$i+1?>"><?=$i + 1?></a>
                    </li>
                @else
                    <li class="page-item">
                        <a href="{{route('pornstars', ['host' => $site->domain])}}?page=<?=$i+1?>"><?=$i + 1?></a>
                    </li>

                @endif
                <?php endfor ?>
            </ul>
        </nav>

    </div>

</main>