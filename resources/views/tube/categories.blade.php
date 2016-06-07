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
    @include('tube._categories')
</section>

<section class="row tags_header">
    <div class="container">
        @foreach($categories as $category)
            <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" target="_blank">
                <div class="col-md-1 col-sm-4 col-xs-4 tube_cat" style="text-align: center;">
                    <img src="{{$category->thumb}}" />
                    <div class="clearfix"></div>
                    <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" target="_blank"><small>{{ str_limit(ucfirst($category->name), $limit = 8 , $end = '...') }} ({{$category->countScenesLang($language->id)}})</small></a>
            </div>
            </a>
        @endforeach
    </div>

    <div class="col-md-12 text-center">
        <?php echo $categories->appends(['q' => $query_string])->render(); ?>
    </div>

</section>


@include('tube._footer')
@include('tube._javascripts')

</body>