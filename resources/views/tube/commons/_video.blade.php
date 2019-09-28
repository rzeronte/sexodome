<main class="main">
    <div class="container">
        <header class="page-header">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <i class="mdi mdi-stars"></i>
                    <h2>{{$video->title}}</h2>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <i class="mdi mdi-message"></i>
                    <h3>{{trans('tube.related_scenes')}}</h3>
                </div>
            </div>
        </header>

        <div class="row">
            <div class="col-md-8">
                <?php echo $video->cleanIframeHtml();?>

                 @if ($video->description && $video->description != 'NULL')
                    <p class="scene-description">{{$video->description}}</p>
                @endif

                <div class="clearfix"></div>

                @foreach ($video->categories()->get() as $category)
                    <?php $translation = $category->translations()->where('language_id', App::make('sexodomeKernel')->getLanguage()->id)->first(); ?>
                    <a href="{{ route('category', array('profile' => App::make('sexodomeKernel')->getSite()->domain, 'permalink'=> $translation->permalink )) }}" class="tag tag-category">
                        <i class="mdi mdi-label"></i> {{ ucwords($translation->name)}}
                    </a>
                @endforeach

                <div class="clearfix"></div>
            </div>

            <div class="col-md-4">
                @include('tube.commons._related', ['videos' => $related])
            </div>

        </div>

        @include('tube.commons._iframe_share')

    </div>

</main>
