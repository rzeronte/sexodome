<?php $agent = new \Jenssegers\Agent\Agent() ?>
<main class="main">
    <div class="container">
        <header class="page-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <i class="mdi mdi-home"></i>
                    <h2>
                        {{number_format($categories->total(), 0, ",", ".")}} {{trans('tube.h1_info_categories')}}, @if (isset($scenes)){{ number_format($scenes->total(), 0, ".", ",") }} {{trans('tube.h1_info_porn_videos')}} @endif
                        @if (isset($total_scenes)){{ number_format($total_scenes, 0, ",", ".") }} {{trans('tube.h1_info_porn_videos')}} @endif
                        @yield('orders')
                    </h2>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                </div>
            </div>
        </header>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                    <div class="thumbnail">
                        <figure>
                            <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" _target="_blank">
                            <span class="thumb-image">
                            <span class="floater-b-c">{{ucwords($category->name)}}</span>
                            <span class="floater-t-l">{{number_format($category->nscenes, 0, ",", ".")}} videos</span>
                                <?php $srcThumbnail = asset('/thumbnails/'.md5($category->thumb).".jpg")?>
                                <img class="img" src="{{$srcThumbnail}}" alt="{{ucwords($category->name)}}" style="height: 100%;">
                            </span>
                            </a>
                        </figure>
                    </div>
                </div>
            @endforeach

        </div>

        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($i = 0; $i < $categories->lastPage(); $i++): ?>
                    @if (($i+1) == Request::get('page'))
                        <li class="page-item active">
                            <a href="{{route('categories', ['host' => $site->domain])}}?page=<?=$i+1?>"><?=$i + 1?></a>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{route('categories', ['host' => $site->domain])}}?page=<?=$i+1?>"><?=$i + 1?></a>
                        </li>
                    @endif
                <?php endfor ?>
            </ul>
        </nav>

    </div>


</main>

