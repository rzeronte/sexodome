<main class="main">
    <div class="container">
        <header class="page-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @yield('h2_tag')

                    @if (App::make('sexodomeKernel')->getUA()->isMobile())
                        <span class="badge">{{number_format($categories->total(), 0, ",", ".")}}  {{trans('tube.h1_info_categories')}}</span>
                        @if (isset($total_scenes))<span class="badge">{{ number_format($total_scenes, 0, ",", ".") }} {{trans('tube.h1_info_porn_videos')}}</span>@endif
                    @else
                        <div>
                            {{number_format($categories->total(), 0, ",", ".")}} {{trans('tube.h1_info_categories')}}
                            @if (isset($scenes)){{ number_format($scenes->total(), 0, ".", ",") }} {{trans('tube.h1_info_porn_videos')}} @endif
                            @if (isset($total_scenes)){{ number_format($total_scenes, 0, ",", ".") }} {{trans('tube.h1_info_porn_videos')}} @endif
                            @yield('orders')
                        </div>
                    @endif
                </div>
            </div>
        </header>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                    <div class="thumbnail">
                        <figure>
                            <a href="{{route('category', ['profile' => App::make('sexodomeKernel')->getSite()->domain, 'permalink'=>str_slug($category->name)])}}" target="_blank">
                                <span class="thumb-image">
                                <h4 class="floater-b-c">{{ucwords($category->name)}}</h4>
                                <span class="floater-t-l">{{number_format($category->nscenes, 0, ",", ".")}} videos</span>
                                    @if ($category->thumb_locked == 1)
                                        <?php $srcThumbnail = $category->thumb?>
                                    @else
                                        <?php $srcThumbnail = asset('/thumbnails/'.md5($category->thumb).".jpg")?>
                                    @endif

                                    <img class="img" src="{{$srcThumbnail}}" alt="{{ucwords($category->name)}}" style="height: 100%;">
                                </span>
                            </a>
                        </figure>
                    </div>
                </div>
            @endforeach

        </div>

        <nav aria-label="Pagination">
            @yield('paginator')
        </nav>

    </div>


</main>

