<section>
    <div class="container header_title_section">
        @if (isset($categoryTranslation))
            <h2 class="col-md-6">
                {{$categoryTranslation->name}}
                @if (isset($scenes))
                    ({{number_format($scenes->total(), 0, ".", ",")}} {{trans('tube.h1_info_porn_videos')}})
                @endif
            </h2>
            <div class="link_order_container">
                <a href="{{route('category', ['profile' => $site->getHost(), 'permalink' => $permalinkCategory, 'order' => 'popular'])}}" class="link_order"> @if (Request::get('order') == 'popular') <b>{{trans('tube.btn_order_mostpopular')}}</b> @else {{trans('tube.btn_order_mostpopular')}} @endif </a>
                <a href="{{route('category', ['profile' => $site->getHost(), 'permalink' => $permalinkCategory, 'order' => 'newest'])}}" class="link_order">@if (Request::get('order') == false || Request::get('order') == 'newest') <b>{{trans('tube.btn_order_news')}}</b> @else {{trans('tube.btn_order_news')}} @endif </a>
            </div>
        @endif

        @if (isset($pornstar))
            <h2>
                {{$pornstar->name}}
                @if (isset($scenes))
                    ({{$scenes->total()}} {{trans('tube.count_porn_videos')}})
                @endif
            </h2>
        @endif

        @if ($query_string)
            <h2>
                @if (isset($scenes))
                    {{$scenes->total()}} {{trans('tube.count_porn_videos_for')}} '<b>{{$query_string}}</b>'
                @endif
            </h2>
        @endif

    </div>

    <div class="container">
            <?php $i=0 ?>
            @foreach ($scenes as $scene)

                <?php
                $i++;
                // select preview thumb
                $thumbs = json_decode($scene->thumbs);
                $index = rand(0, count($thumbs)-1);
                ?>

                <div class="col-md-2 video_outer col-sm-4 col-xs-4">
                    <figure>
                        <?php $srcThumbnail = "" ?>
                        @if ($scene->thumb_index > 0)
                            <?php $srcThumbnail = htmlspecialchars($thumbs[$scene->thumb_index])?>
                        @else
                            <?php $srcThumbnail = asset('/thumbnails/'.md5($scene->preview).".jpg")?>
                        @endif

                        @if ($scene->channel->embed == 1)
                            <?php $href = route('video', ['profile' => $profile, 'permalink' => $scene->permalink]);?>
                            <a href="{{$href}}" class="link_image" title="{{$scene->title}}" @if ($site->google_analytics) onclick="trackOutboundLink('{{$href}}', '{{strtolower($scene->channel->name)}}');return false;" @endif target="_blank">
                                <img class="border-thumb" src="{{$srcThumbnail}}" onmouseout="outThumb(this)" onmouseover="changeThumb(this)" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop" alt="{{$scene->title}}"/>
                            </a>
                        @else
                            <?php $href = route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]); ?>
                            <a href="{{ route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]) }}" target="_blank"  class="link_image" title="{{$scene->title}}" @if ($site->google_analytics) onclick="trackOutboundLink('{{$href}}', '{{strtolower($scene->channel->name)}}');return false;" @endif  target="_blank">
                                <img class="border-thumb" src="{{$srcThumbnail}}" onmouseout="outThumb(this)" onmouseover="changeThumb(this)" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop" alt="{{$scene->title}}"/>
                            </a>
                        @endif

                        <div class="info_video">
                            @if ($scene->channel->embed == 1)
                                <?php $href = route('video', ['profile' => $profile, 'permalink' => $scene->permalink]); ?>
                            @else
                                <?php $href = route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]) ?>
                            @endif

                            <a class="title" href="{{$href}}" alt="{{$scene->title}}" @if ($site->google_analytics) onclick="trackOutboundLink('{{$href}}', '{{strtolower($scene->channel->name)}}');return false;" @endif target="_blank">
                                {{str_limit($scene->title, 40, $end = '...')}}
                            </a>

                            <div class="clearfix"></div>
                            <?php $agent = new \Jenssegers\Agent\Agent() ?>

                            <div class="extra_info">
                                <small>
                                    {{gmdate("i:s", $scene->duration)}},
                                    {{$scene->updated_at->diffForHumans()}}
                                    {{--@if (!$agent->isMobile())--}}
                                    {{--, {{$scene->clicks()->count()+0}} {{trans('tube.views')}}--}}
                                    {{--@endif--}}
                                    {{--<a href="#" class="channel_link">{{strtolower($scene->channel->name)}}</a>--}}
                                </small>
                            </div>

                            @if (!$agent->isMobile())
                                @foreach ($scene->categories()->limit(3)->get() as $category)
                                    <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                                    <?php if ($translation && count(explode(" ", $translation->name)) <=2): ?>
                                    <a class="category_link" href="{{ route('category', array('profile' => $profile, 'permalink'=> str_slug($translation->name) )) }}">{{strtolower($translation->name)}}</a>
                                    <?php endif?>
                                @endforeach
                            @endif


                        </div>
                    </figure>
                </div>
            @endforeach

    </div>
    <div class="clearfix"></div>

    @if (!isset($removePaginator))
        <div class="row text-center">
            <?php echo $scenes->appends(['q' => $query_string])->render(); ?>
        </div>
    @endif
</section>
