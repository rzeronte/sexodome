<div class="container header_title_section">
    @if (isset($categoryTranslation))
        <h2>
            {{$categoryTranslation->name}}
            @if (isset($scenes))
                ({{$scenes->total()}} porn videos)
            @endif
        </h2>
    @endif

    @if (isset($pornstar))
        <h2>
            {{$pornstar->name}}
            @if (isset($scenes))
                ({{$scenes->total()}} porn videos)
            @endif

        </h2>
    @endif

    @if ($query_string)
        <h2>
            @if (isset($scenes))
                {{$scenes->total()}} porn videos  for '<b>{{$query_string}}</b>'
            @endif
        </h2>
    @endif
</div>

<div class="container">
    <div class="row">
        <?php $i=0 ?>
        @foreach ($scenes as $scene)

            <?php
            $i++;
            // select preview thumb
            $thumbs = json_decode($scene->thumbs);
            $index = rand(0, count($thumbs)-1);
            ?>

            <div class="col-md-2 video_outer col-sm-6 col-xs-6">
                <figure>
                    <?php $srcThumbnail = "" ?>
                    @if ($scene->thumb_index > 0)
                        <?php $srcThumbnail = htmlspecialchars($thumbs[$scene->thumb_index])?>
                    @else
                        <?php $srcThumbnail = htmlspecialchars($scene->preview)?>
                    @endif

                    @if ($scene->channel->embed == 1)
                        <a href="{{ route('video', ['profile' => $profile, 'permalink' => $scene->permalink]) }}" class="link_image">
                            <img class="border-thumb" src="{{$srcThumbnail}})" onmouseout="outThumb(this)" onmouseover="changeThumb(this)" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop"/>
                        </a>
                    @else
                        <a href="{{ route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]) }}" target="_blank"  class="link_image">
                            <img class="border-thumb" src="{{$srcThumbnail}}" onmouseout="outThumb(this)" onmouseover="changeThumb(this)" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop"/>
                        </a>
                    @endif

                    <div class="info_video">

                        <a class="title" href="@if ($scene->channel->embed == 1) {{ route('video', ['profile' => $profile, 'permalink' => $scene->permalink]) }} @else {{ route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]) }} @endif" alt="{{$scene->title}}">
                            {{str_limit($scene->title, 25, $end = '...')}}
                        </a>

                        <div class="clearfix"></div>

                        <div class="extra_info">
                            <small>
                                {{gmdate("i:s", $scene->duration)}},
                                {{$scene->updated_at->diffForHumans()}},
                                {{$scene->clicks()->count()+0}} views,
                                <a href="#" class="channel_link">{{strtolower($scene->channel->name)}}</a>
                            </small>
                        </div>

                        @foreach ($scene->categories()->limit(3)->get() as $category)
                            <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                            <?php if ($translation && count(explode(" ", $translation->name)) <=2): ?>
                            <a class="category_link" href="{{ route('category', array('profile' => $profile, 'permalink'=> str_slug($translation->name) )) }}">{{$translation->name}}</a>
                            <?php endif?>
                        @endforeach


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

</div>
