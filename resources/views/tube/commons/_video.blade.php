<section class="row">
    <div class="container">
        <h2 class="video_header">{{$video->title}}</h2>
        <?php
        $iframe = $video->iframe;
        $pattern = "/width=\"[0-9]*\"/";
        $iframe = preg_replace($pattern, "width='100%'", $iframe);
        $pattern2 = "/width=\"[0-9]*+px\"/";
        $iframe = preg_replace($pattern2, "width='100%'", $iframe);
        $pattern3 = "/height=\"[0-9]*\"/";
        $iframe = preg_replace($pattern3, "height='500px'", $iframe);
        ?>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-7">
                <?php echo $iframe;?>
                @if ($video->description && $video->description != 'NULL')
                    <p>{{$video->description}}</p>
                @endif
            </div>
            <div class="col-md-2">
                @foreach ($video->categories()->get() as $category)
                    <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                    <a href="{{ route('category', array('profile' => $profile, 'permalink'=> $translation->permalink )) }}" class="tag tag-category">
                        <i class="glyphicon glyphicon-film"></i> {{ $translation->name}}
                    </a>
                    <div class="clearfix"></div>
                @endforeach
            </div>
            <div class="col-md-3">
                @if ($site->banner_video1 != "")
                    <?=$site->banner_video1 ?>
                @endif

                <div class="clearfix"></div>

                @if ($site->banner_video2 != "")
                    <?=$site->banner_video2 ?>
                @endif
            </div>

        </div>

        @include('tube.commons._iframe_share')
        @include('tube.commons._related', ['videos'=>$related])

    </div>
</section>
