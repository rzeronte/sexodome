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

<section class="container tags_header">
    @include('tube._categories', ["categories" =>$categories_head])
</section>

<section class="row tags_header">
    <div class="container">
        <?php $i = 1; ?>
        <div class="clearfix"></div>
        <div class="col-md-1"></div>
        @foreach($categories as $category)
            <div class="col-md-1 col-sm-4 col-xs-4 tube_cat" style="text-align: center;padding:0:margin:0;">
                <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" target="_blank">
                    <img src="{{$category->thumb}}" />
                </a>

                <div class="clearfix"></div>

                <div class="text_link">
                    <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" target="_blank">{{ str_limit(ucfirst($category->name), $limit = 13 , $end = '...') }} ({{$category->countScenesLangAndSite($language->id, $site->id)}})</a>
                </div>
            </div>
            @if ($i % 10 == 0 and $i != 0)
                    <div class="clearfix"></div>
                <div class="col-md-1"></div>
                @endif
            <?php $i++; ?>
        @endforeach
    </div>

    <div class="col-md-12 text-center">
        <?php echo $categories->appends(['q' => $query_string])->render(); ?>
    </div>

</section>


@include('tube._footer')
@include('tube._javascripts')

</body>