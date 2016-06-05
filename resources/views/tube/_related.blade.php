<div class="container">
    <div class="row media-grid content_video_posts">

        @foreach ($videos as $scene)
            <?php
            // select preview thumb
            $thumbs = json_decode($scene->thumbs);
            $index = rand(0, count($thumbs)-1);
            ?>

            <article class="col-sm-2 video_post postType3">
                <div class="inner row m0">
                    <a href="{{ route('video', ['profile' => $profile, 'permalink' => $scene->permalink]) }}"><div class="row screencast m0">
                            <img src="<?=htmlspecialchars($thumbs[$index])?>" class="" style="width:100%;margin:0 !important;min-height:150px;" alt="{{$scene->title}}" data-thumbs="{{$scene->thumbs}}" data-current-frame="{{$index}}" data-status="stop" onmouseout="outThumb(this)" onmouseover="changeThumb(this)"/>
                            {{--<div class="play_btn"></div>--}}
                            <div class="media-length">{{gmdate("i:s", $video->duration)}}</div>
                        </div></a>
                    <div class="row m0 post_data">
                        <div class="row m0 post_container_extras" style="vertical-align: bottom">
                            <div class="fleft" style="width:100%;">
                                <small style="float:left;" class="date_published">{{$scene->updated_at->diffForHumans()}}</small>
                                <small style="float:left;margin-left: 10px;">{{gmdate("i:s", $scene->duration)}}</small>
                                <small class="eyethumbnail"><i class="glyphicon glyphicon-eye-open"></i> {{$scene->clicks()->count()+0}}</small>
                                <br/>
<!--                        @foreach ($scene->tags()->limit(4)->get() as $tag)
                                <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                                <?php if ($translation): ?>
                                    <a href="{{ route('tag', array('permalink'=> $translation->permalink )) }}">{{$translation->name}}</a>
                                <?php endif;?>
                            @endforeach
-->
                            @foreach ($scene->categories()->limit(4)->get() as $category)
                                <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                                <?php if ($translation): ?>
                                <a href="{{ route('category', array('profile' => $profile, 'permalink'=> str_slug($translation->name) )) }}">{{$translation->name}}</a>
                                <?php endif;?>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach

        <div class="clearfix"></div>
    </div>

</div>
