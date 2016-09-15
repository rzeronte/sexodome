<div class="container">
    <div class="container header_title_section">
        <h2>
            {{$pornstars->total()}} pornstars
        </h2>
    </div>

    <div class="clearfix"></div>

    @foreach($pornstars as $pornstar)
        <div class="col-md-2 col-sm-4 col-xs-4 pornstar_outer">
            <figure>
                <a href="{{route('pornstar', ['profile' => $profile, 'permalinkPornstar'=>str_slug($pornstar->name)])}}" class="link_image" title="{{$pornstar->name}}">
                    <img src="{{$pornstar->thumbnail}}" class="border-thumb"/>
                </a>

                <div class="clearfix"></div>

                <div class="pornstar_info">
                    <a class="pornstar_link" title="{{$pornstar->name}}" href="{{route('pornstar', ['profile' => $profile, 'permalinkPornstar'=>str_slug($pornstar->name)])}}">{{ str_limit(ucfirst($pornstar->name), $limit = 25 , $end = '...') }}</a>
                </div>
            </figure>
        </div>
    @endforeach

</div>

<div class="col-md-12 text-center">
    <?php echo $pornstars->appends(['q' => $query_string])->render(); ?>
</div>
