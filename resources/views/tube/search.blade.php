<!DOCTYPE html>
<html>
    <head>
        @include('tube.commons._head')
    </head>

    <body class="homepage">
        @include('tube.commons._header')

        @section('orders')
            <div class="link_order_container">
                <a href="{{route('categories', ['order' => 'latest'])}}" class="btn btn-secondary btn-sm active link_order">Latest porn videos</a>
                <a href="{{route('categories', ['order' => 'newest'])}}" class="btn btn-secondary btn-sm active link_order">Newest porn videos</a>
            </div>
        @endsection

        @include('tube.commons._videos')

        @if ($language->iframe_src != "")
            <section class="container">
                @include('tube.commons._iframe_network')
            </section>
        @endif

        @include('tube.commons._footer')
        @include('tube.commons._javascripts')

    </body>
</html>
