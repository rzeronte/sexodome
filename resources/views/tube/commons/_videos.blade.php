<?php $agent = new \Jenssegers\Agent\Agent() ?>

<main class="main">
    <div class="container">
        <header class="page-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    @yield('h2_tag')
                </div>

                @yield('orders')
            </div>
        </header>

        <div class="row">
            <?php $i=0 ?>
            @foreach ($scenes as $scene)

                <?php
                $i++;
                // select preview thumb
                $thumbs = json_decode($scene->thumbs);
                $index = rand(0, count($thumbs)-1);
                ?>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                    <div class="thumbnail">
                        <figure>
                            <?php $srcThumbnail = "" ?>
                            @if ($scene->thumb_index > 0)
                                <?php $srcThumbnail = htmlspecialchars($thumbs[$scene->thumb_index])?>
                            @else
                                <?php $srcThumbnail = asset('/thumbnails/'.md5($scene->preview).".jpg")?>
                            @endif

                            <?php $date = new \Jenssegers\Date\Date($scene->created_at)?>
                            <?php \Jenssegers\Date\Date::setLocale(App::getLocale());?>

                            @if ($scene->channel->embed == 1)
                                <?php $href = route('video', ['profile' => $profile, 'permalink' => $scene->permalink]);?>
                                <a href="{{$href}}" class="img link_image" title="{{$scene->title}}" @if ($site->google_analytics) onclick="trackOutboundLink('{{$href}}', '{{strtolower($scene->channel->name)}}');return false;" @endif target="_blank">
                                   <span class="thumb-image">
                                        <span class="floater-t-l"><i class="mdi mdi-access-time"></i> {{gmdate("i:s", $scene->duration)}}</span>
                                        <span class="floater-b-l">{{$date->diffForHumans()}}</span>
                                        <img class="img" src="{{$srcThumbnail}}" onmouseout="outThumb(this)" onmouseover="changeThumb(this)" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop" alt="{{$scene->title}}"/>
                                   </span>
                                </a>
                            @else
                                <?php $href = route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]); ?>
                                <a href="{{ route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]) }}" rel="nofollow" target="_blank"  class="img link_image" title="{{$scene->title}}" @if ($site->google_analytics) onclick="trackOutboundLink('{{$href}}', '{{strtolower($scene->channel->name)}}');return false;" @endif  target="_blank">
                                   <span class="thumb-image">
                                        <span class="floater-t-l"><i class="mdi mdi-access-time"></i> {{gmdate("i:s", $scene->duration)}}</span>
                                        <span class="floater-b-l">{{$date->diffForHumans()}}</span>
                                        <img class="img" src="{{$srcThumbnail}}" onmouseout="outThumb(this)" onmouseover="changeThumb(this)" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop" alt="{{$scene->title}}"/>
                                   </span>
                                </a>
                            @endif

                            <figcaption>
                                @if ($scene->channel->embed == 1)
                                    <?php $href = route('video', ['profile' => $profile, 'permalink' => $scene->permalink]); ?>
                                @else
                                    <?php $href = route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]) ?>
                                @endif

                                <a target="_blank" href="{{$href}}" @if ($scene->channel->embed != 1) rel="nofollow" @endif @if ($site->google_analytics) onclick="trackOutboundLink('{{$href}}', '{{strtolower($scene->channel->name)}}');return false;" @endif>
                                    <h5>{{ ucfirst(str_limit($scene->title, 40, $end = '...')) }}</h5>
                                </a>

                                <ul class="list-inline">
                                    @foreach ($scene->categories()->limit(3)->get() as $category)
                                        <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                                        <?php if ($translation && count(explode(" ", $translation->name)) <=2): ?>
                                            <li>
                                                <span class="label label-default">
                                                    <a href="{{ route('category', array('profile' => $profile, 'permalink'=> str_slug($translation->name) )) }}" title="Tag">
                                                        <i class="mdi mdi-label"></i> {{strtolower($translation->name)}}
                                                    </a>
                                                </span>
                                            </li>
                                        <?php endif?>
                                    @endforeach
                                </ul>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                @if ($i%6 == 0)
                    <div class='clearfix'></div>
                @endif
            @endforeach

        </div>

        @if (!isset($removePaginator))
            @yield('paginator')
        @endif
    </div>
</main>
