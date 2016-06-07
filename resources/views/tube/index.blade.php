<!DOCTYPE html>
<html>

<head>
    @include('tube._head')
    <link rel="stylesheet" href="{{asset('site.css')}}">
    <link href='https://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
</head>

<body>
    <section class="row header">
        @include('tube._header')
    </section>

    <section class="container tags_header">
        @include('tube._categories')
    </section>

    <section class="container videos">
        <div class="col-md-12">
            @include('tube._videos')
        </div>
    </section>

    @if ($language->iframe_src != "")
        <section class="container">
            @include('tube._iframe_network')
        </section>
    @endif

    @include('tube._footer')
    @include('tube._javascripts')
    @include('tube._theme')

</body>
