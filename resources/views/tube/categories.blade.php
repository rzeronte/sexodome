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
        @foreach($categories as $category)
            <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" target="_blank">
            <div class="col-md-2 col-sm-4 col-xs-4" style="text-align: center;">
                    <img src="{{$category->thumb}}" class="img-thumbnail"/>
                <br/>

                <a href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}" target="_blank"><small>{{ucfirst($category->name)}} <br/>({{$category->countScenesLang($language->id)}})</small></a>
                <br/>
                <br/>
            </div>
            </a>
        @endforeach
    </div>
</section>


@include('tube._footer')
@include('tube._javascripts')
@include('tube._theme')

</body>