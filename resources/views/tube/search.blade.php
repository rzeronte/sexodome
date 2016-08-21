<!DOCTYPE html>
<html>

<head>
    @include('tube.commons._head')
</head>

<body>
    @include('tube.commons._header')

    <section>
        @include('tube.commons._videos')
    </section>

    @if ($language->iframe_src != "")
        <section class="container">
            @include('tube.commons._iframe_network')
        </section>
    @endif

    @include('tube.commons._footer')
    @include('tube.commons._javascripts')
</body>
