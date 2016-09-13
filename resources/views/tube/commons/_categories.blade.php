<?php $agent = new \Jenssegers\Agent\Agent() ?>
<section>
    <div class="container @if ($agent->isDesktop()) header_title_section @endif text-center @endif">
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
                    <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" class="link_image" title="{{$category->name}}">
                        <img src="{{$category->thumb}}" class="border-thumb"/>
                    </a>

                    <div class="category_info">
                        <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" class="link_category">{{ str_limit(ucfirst($category->name), $limit = 30 , $end = '...') }}</a>
                        <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" class="link_nvideos">({{$category->nscenes}})</a>
                    </div>
                </figure>

            </div>
        @endforeach

    </div>

    <div class="col-md-12 text-center">
        <?php echo $categories->appends(['q' => $query_string])->render(); ?>
    </div>

</section>
