@section('pagination_seo')
    @if (isset($pornstars))
        @if ( $pornstars->currentPage() > 1)
            <link rel="prev" href="{{  route('pornstars_page', ['profile' => $profile, 'page' => $pornstars->currentPage() - 1]) }} " />
        @endif

        @if ( $pornstars->currentPage() < ($pornstars->lastPage()))
            <link rel="next" href="{{ route('pornstars_page', ['profile' => $profile, 'page' => $pornstars->currentPage() + 1])}}" />
        @endif
    @endif
@endsection

@section('paginator')
    @include('tube.paginators._paginator_pornstars', ['paginator' => $pornstars, 'route_name' => 'pornstars_page'])
@endsection

@section('h2_tag')
    <h2><i class="mdi mdi-stars"></i> {{ $site->getH2Pornstars() }}</h2>
@endsection
<!DOCTYPE html>
<html>
    <head>
        @include('tube.commons._head')
    </head>

    <body>
        @include('tube.commons._header')
        @include('tube.commons._pornstars')
        @include('tube.commons._footer')
        @include('tube.commons._javascripts')
    </body>
</html>