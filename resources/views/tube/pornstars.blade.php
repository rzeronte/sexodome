<!DOCTYPE html>
<html>

<head>
    @include('tube._head')
    <link rel="stylesheet" href="{{asset('site.css')}}">
</head>

<body>
<section class="row header">
    @include('tube._header')
</section>

<section class="row tags_header">
    <div class="container">
        <div class="clearfix"></div>

        <div class="row">
            @foreach($pornstars as $pornstar)
                <div class="col-md-2 col-sm-4 col-xs-4 tube_cat">
                    <a href="{{route('pornstar', ['profile' => $profile, 'permalinkPornstar'=>str_slug($pornstar->name)])}}" class="img_link">
                        <img src="{{$pornstar->thumbnail}}"/>
                    </a>

                    <div class="clearfix"></div>

                    <div class="text_link" style="float:left; display: inline-block">
                        <a href="{{route('pornstar', ['profile' => $profile, 'permalinkPornstar'=>str_slug($pornstar->name)])}}">{{ str_limit(ucfirst($pornstar->name), $limit = 13 , $end = '...') }}</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div class="col-md-12 text-center">
        <?php echo $pornstars->appends(['q' => $query_string])->render(); ?>
    </div>

</section>


@include('tube._footer')
@include('tube._javascripts')

</body>