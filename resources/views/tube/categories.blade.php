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
        <div class="clearfix"></div>

        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-2 col-sm-4 col-xs-4 tube_cat">
                    <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" class="img_link">
                        <img src="{{$category->thumb}}"/>
                    </a>

                    <div class="clearfix"></div>

                    <div class="text_link" style="float:left; display: inline-block">
                        <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}">{{ str_limit(ucfirst($category->name), $limit = 13 , $end = '...') }} ({{$category->nscenes}})</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div class="col-md-12 text-center">
        <?php echo $categories->appends(['q' => $query_string])->render(); ?>
    </div>

</section>


@include('tube._footer')
@include('tube._javascripts')

</body>