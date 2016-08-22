<section class="row">
    <div class="container">
        <h2>{{$video->title}}</h2>
        <?php
        $iframe = $video->iframe;
        $pattern = "/width=\"[0-9]*\"/";
        $iframe = preg_replace($pattern, "width='100%'", $iframe);
        $pattern2 = "/width=\"[0-9]*+px\"/";
        $pattern = "/width='[0-9]*'/";
        $iframe = preg_replace($pattern, "width='100%'", $iframe);
        $pattern2 = "/width='[0-9]*+px'/";

        $iframe = preg_replace($pattern2, "width='100%'", $iframe);
        ?>
        <div class="row">
            <div class="col-md-10">
                <?php echo $iframe;?>
                    @if ($video->description && $video->description != 'NULL')
                        <p>{{$video->description}}</p>
                    @endif

            </div>
            <div class="col-md-2">
                @foreach ($video->categories()->limit(5)->get() as $category)
                    <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                    <a href="{{ route('category', array('profile' => $profile, 'permalink'=> $translation->permalink )) }}" class="tag tag-category">
                        <small>{{ $translation->name}}</small>
                    </a>
                        <div class="clearfix"></div>
                @endforeach
                @foreach ($video->tags()->limit(5)->get() as $tag)
                    <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                    <a href="{{ route('tag', array('profile' => $profile,'permalink'=> $translation->permalink )) }}" class="tag tag-video">
                        <small>{{ $translation->name}}</small>
                    </a>
                        <div class="clearfix"></div>
                @endforeach

            </div>

        </div>

        @include('tube.commons._iframe_share')
        @include('tube.commons._related', ['videos'=>$related])

    </div>
</section>
