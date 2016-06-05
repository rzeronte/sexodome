<!DOCTYPE html>
<html>

<head>
    @include('TubeFront::layout._head')
    <link rel="stylesheet" href="{{asset('site.css')}}">
    <link href='https://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
</head>

<body>
    <section class="row header">
        @include('TubeFront::layout._header')
    </section>

    <section class="row">
        @include('TubeFront::layout._tags_index')
    </section>


    @include('TubeFront::layout._footer')
    @include('TubeFront::layout._javascripts')
    @include('tube._theme')

</body>