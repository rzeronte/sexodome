@if (isset($categoryTranslation))
    <h2 class="category_header"><i class="glyphicon glyphicon-th"></i>  {{$categoryTranslation->name}}</h2>
@endif

<div class="row media-grid content_video_posts">
    <?php $i=0 ?>
    @foreach ($scenes as $scene)

        <?php
        $i++;
        // select preview thumb
        $thumbs = json_decode($scene->thumbs);
        $index = rand(0, count($thumbs)-1);
        ?>

        <article class="col-sm-2 video_post postType3">
            <div class="inner row m0" style="border:none;">
                <?php $srcThumbnail = "" ?>
                @if ($scene->thumb_index > 0)
                    <?php $srcThumbnail = htmlspecialchars($thumbs[$scene->thumb_index])?>
                @else
                    <?php $srcThumbnail = htmlspecialchars($scene->preview)?>
                @endif

                <div class="tubethumbnail" style="background-image: url({{$srcThumbnail}});" onmouseout="outThumb(this)" onmouseover="changeThumb(this)" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop">
                    @if ($scene->channel->embed == 1)
                    <a href="{{ route('video', ['profile' => $profile, 'permalink' => $scene->permalink]) }}" class="post_title">
                        {{str_limit($scene->title, 150, $end = '...')}}
                    </a>
                    @else
                        <a href="{{ route('out', ['profile' => $profile, 'scene_id' => $scene->id, 'p' => $i]) }}" class="post_title" target="_blank">
                            {{str_limit($scene->title, 150, $end = '...')}}
                        </a>
                    @endif
                </div>

                <div class="row m0 post_data">
                    <div class="row m0 post_container_extras" style="vertical-align: bottom">
                        <div class="fleft">
                            <small style="float:left;" class="date_published">{{$scene->updated_at->diffForHumans()}}</small>
                            <small style="float:left;margin-left: 10px;">{{gmdate("i:s", $scene->duration)}}</small>
                            <small class="eyethumbnail"><i class="glyphicon glyphicon-eye-open"></i> {{$scene->clicks()->count()+0}}</small>
                            <br/>
                            @foreach ($scene->categories()->limit(4)->get() as $category)
                                <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                                <?php if ($translation): ?>
                                    <a href="{{ route('category', array('profile' => $profile, 'permalink'=> str_slug($translation->name) )) }}">{{$translation->name}}</a>
                                <?php endif;?>
                            @endforeach<br/>
                            <a href="#" class="channel_link">{{$scene->channel->name}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    @endforeach

    <div class="clearfix"></div>

    <div class="col-md-12 text-center">
        @if (!isset($removePaginator))
            <?php echo $scenes->appends(['q' => $query_string])->render(); ?>
        @endif
    </div>

</div>
