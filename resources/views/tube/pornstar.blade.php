@section('pagination_seo')
    @if (isset($scenes))
        @if ( $scenes->currentPage() > 1)
            <link rel="prev" href="{{  route('pornstar_page', ['profile' => $profile, 'permalinkPornstar'=> $pornstar->permalink,  'page' => $scenes->currentPage() - 1]) }} " />
        @endif

        @if ( $scenes->currentPage() < ($scenes->lastPage()))
            <link rel="next" href="{{ route('pornstar_page', ['profile' => $profile, 'permalinkPornstar'=> $pornstar->permalink,  'page' => $scenes->currentPage() + 1])}}" />
        @endif
    @endif
@endsection

@section('paginator')
    @if (isset($pornstar))
        @include('tube.paginators._paginator_pornstar', ['paginator' => $scenes, 'route_name' => 'pornstar_page'])
    @endif
@endsection

@section('orders')
    <div class="link_order_container">
        <a href="{{route('categories', ['order' => 'latest'])}}" class="btn btn-secondary btn-sm active link_order">Latest porn videos</a>
        <a href="{{route('categories', ['order' => 'newest'])}}" class="btn btn-secondary btn-sm active link_order">Newest porn videos</a>
    </div>
@endsection

@section('h2_tag')
    <h2><i class="mdi mdi-stars"></i> {{ $site->getH2Pornstar($pornstar->name) }}</h2>
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
