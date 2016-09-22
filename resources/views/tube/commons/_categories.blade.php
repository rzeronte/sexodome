<?php $agent = new \Jenssegers\Agent\Agent() ?>
<section>
    <div class="container @if ($agent->isDesktop()) header_title_section @else text-center header_title_section_mobile @endif">
        <h2>
            {{$categories->total()}} {{trans('tube.h1_info_categories')}}, @if (isset($scenes)){{ number_format($scenes->total(), 0, ".", ",") }} {{trans('tube.h1_info_porn_videos')}} @endif
            @if (isset($total_scenes)){{ number_format($total_scenes, 0, ",", ".") }} {{trans('tube.h1_info_porn_videos')}} @endif
            @yield('orders')
        </h2>
    </div>

    <div class="container">

        @foreach($categories as $category)
            <div class="col-md-2 col-sm-4 col-xs-4 category_outer">
                <figure>
                    <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" class="link_image" title="{{$category->name}}" target="_blank">
                        <?php $srcThumbnail = asset('/thumbnails/'.md5($category->thumb).".jpg")?>
                        <img src="{{$srcThumbnail}}" class="border-thumb" alt="{{$category->name}}"/>
                    </a>

                    <div class="category_info">
                        <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" class="link_category" target="_blank">{{ str_limit(ucfirst($category->name), $limit = 30 , $end = '...') }}</a>
                        <div class="clearfix"></div>
                        <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" class="link_nvideos" target="_blank">{{number_format($category->nscenes, 0, ",", ".")}} videos</a>
                    </div>
                </figure>

            </div>
        @endforeach

    </div>

    <div class="col-md-12 text-center">
        <?php echo $categories->appends(['q' => $query_string])->render(); ?>
    </div>

</section>
