@section('h2_tag')
    <h2><i class="mdi mdi-home"></i> {{$site->getH2Home()}}</h2>
@endsection

@section('paginator')
    @include('tube.paginators._paginator_search', ['paginator' => $scenes, 'route_name' => 'search_page'])
@endsection
<!DOCTYPE html>
<html>
    <head>
        @include('tube.commons._head')
    </head>

    <body class="homepage">
        @include('tube.commons._header')

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
